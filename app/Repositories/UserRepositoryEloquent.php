<?php

namespace App\Repositories;

use App\Contracts\Repositories\UserRepository;
use App\Exceptions\Api\ActionException;
use App\Eloquent\Book;
use App\Eloquent\UpdateBook;
use App\Eloquent\Office;
use App\Eloquent\Category;
use App\Eloquent\UserFollow;
use App\Eloquent\Notification;
use Illuminate\Support\Facades\Event;
use Carbon\Carbon;
use Illuminate\Pagination\Paginator;

class UserRepositoryEloquent extends AbstractRepositoryEloquent implements UserRepository
{
    protected $userSelect = [
        'users.id',
        'name',
        'email',
        'phone',
        'code',
        'position',
        'role',
        'office_id',
        'avatar',
        'tags',
        'employee_code',
        'workspaces',
    ];

    public function model()
    {
        return new \App\Eloquent\User;
    }

    public function create($request)
    {
        $input = [
            'name' => $request['name'],
            'email' => $request['email'],
            'password' => $request['password'],
        ];

        return $this->model()->create($input);
    }


    public function getCurrentUser1($email)
    {
        return $this->model()->where('email', $email)->first();
    }
    public function getCurrentUser($userFromAuthServer)
    {
        $userInDatabase = $this->model()->whereEmail($userFromAuthServer['email'])->first();
        $workspaceInfo = $userFromAuthServer['workspaces'][0] ?: NULL;
        $currentUser = $userInDatabase;
        if (isset($workspaceInfo['id'])) {
            $wsmWorkspace = app(Office::class)->where('wsm_workspace_id', $workspaceInfo['id'])->first();
            if($wsmWorkspace) {
                $wsmWorkspace->update([
                    'name' => $workspaceInfo['name'],
                    'area' => $workspaceInfo['name'],
                ]);
            } else {
                $wsmWorkspace = app(Office::class)->create([
                    'name' => $workspaceInfo['name'],
                    'area' => $workspaceInfo['name'],
                    'wsm_workspace_id' => $workspaceInfo['id'],
                ])->fresh();
            }
        }
        $userOfficeId = $wsmWorkspace['id'] ?: NULL;
        if ($userInDatabase) {
            if(in_array($userFromAuthServer['email'], config('settings.email_admin'))) {
                $currentUser->update([
                    'name' => $userFromAuthServer['name'],
                    'email' => $userFromAuthServer['email'],
                    'avatar' => $userFromAuthServer['avatar'],
                    'office_id' => $userOfficeId,
                    'employee_code' => $userFromAuthServer['employee_code'],
                    'role' => config('settings.admin'),
                ]);
            } else {
                $currentUser->update([
                    'name' => $userFromAuthServer['name'],
                    'email' => $userFromAuthServer['email'],
                    'avatar' => $userFromAuthServer['avatar'],
                    'office_id' => $userOfficeId,
                    'employee_code' => $userFromAuthServer['employee_code'],
                    'role' => config('settings.user'),
                ]);
            }
        } else {
            if(in_array($userFromAuthServer['email'], config('settings.email_admin'))) {
                $currentUser = $this->model()->create([
                    'name' => $userFromAuthServer['name'],
                    'email' => $userFromAuthServer['email'],
                    'avatar' => $userFromAuthServer['avatar'],
                    'office_id' => $userOfficeId,
                    'employee_code' => $userFromAuthServer['employee_code'],
                    'role' => config('settings.admin'),
                ])->fresh();
            } else {
                $currentUser = $this->model()->create([
                    'name' => $userFromAuthServer['name'],
                    'email' => $userFromAuthServer['email'],
                    'avatar' => $userFromAuthServer['avatar'],
                    'office_id' => $userOfficeId,
                    'employee_code' => $userFromAuthServer['employee_code'],
                    'role' => config('settings.user'),
                ])->fresh();
            }
        }

        return $currentUser;
    }

    public function getDataBookOfUser($id, $action, $select = ['*'], $with = [], $officeId = '')
    {
        if (
            in_array($action, array_keys(config('model.book_user.status')))
            && in_array(config('model.book_user.status.' . $action), array_values(config('model.book_user.status')))
        ) {
            return $this->model()->findOrFail($id)->books()
                ->getBookByOffice($officeId)
                ->with($with)
                ->wherePivot('status', config('model.book_user.status.' . $action))
                ->paginate(config('paginate.default'), $select);
        }

        if ($action == config('model.user_sharing_book')) {
            return $this->model()->findOrFail($id)->owners()
                ->getBookByOffice($officeId)
                ->with(array_merge($with, [
                        'usersReading' => function($query) {
                            $query->select(array_merge($this->userSelect, ['owner_id']))
                                ->where('book_user.owner_id', $this->user->id);
                            $query->orderBy('book_user.created_at', 'ASC')->limit(1);
                        },
                        'usersWaiting' => function($query) {
                            $query->select('id', 'name', 'avatar', 'position', 'email')
                                ->where('book_user.owner_id', $this->user->id);
                            $query->orderBy('book_user.created_at', 'ASC');
                        },
                        'usersReturning' => function($query) {
                            $query->select('id', 'name', 'avatar', 'position', 'email')
                                ->where('book_user.owner_id', $this->user->id);
                            $query->orderBy('book_user.created_at', 'ASC')->limit(1);
                        }
                    ])
                )
                ->paginate(config('paginate.default'), $select);
        }

        if ($action == config('model.user_reviewed_book')) {
            return $this->model()->findOrFail($id)->reviews()
                ->with($with)
                ->paginate(config('paginate.default'), $select);
        }
    }

    public function addTags(string $tags = null)
    {
        $this->user->update([
            'tags' => $tags,
        ]);
    }

    public function getInterestedBooks($dataSelect = ['*'], $with = [], $officeId = '')
    {
        if ($this->user->tags) {
            $tags = explode(',', $this->user->tags);

            return app(Book::class)
                ->getLatestBooks($dataSelect, $with)
                ->getBookByOffice($officeId)
                ->whereIn('category_id', $tags)
                ->paginate(config('paginate.default'));
        }

        return app(Book::class)
            ->getLatestBooks($dataSelect, $with)
            ->getBookByOffice($officeId)
            ->paginate(config('paginate.default'));
    }

    public function show($id)
    {
        return $this->model()->findOrFail($id);
    }

    public function ownedBooks($dataSelect = ['*'], $with = [])
    {
        $books = app(Book::class)
            ->select($dataSelect)
            ->with(array_merge($with, ['userReadingBook' => function($query) {
                $query->select('id', 'name', 'avatar', 'position');
            }]))
            ->where('owner_id', $this->user->id)
            ->paginate(config('paginate.default'));

        foreach ($books->items() as $book) {
            $book->user_reading_book = $book->userReadingBook->first();
            unset($book['userReadingBook']);
        }

        return $books;
    }

    public function getListWaitingApprove($dataSelect = ['*'], $with = [], $officeId = '')
    {
        $books = $this->user->owners()
            ->select($dataSelect)
            ->with(array_merge($with, [
                'usersWaiting' => function($query) {
                    $query->select('id', 'name', 'avatar', 'position')
                        ->where('book_user.owner_id', $this->user->id);
                    $query->orderBy('book_user.created_at', 'ASC');
                },
                'usersReturning' => function($query) {
                    $query->select('id', 'name', 'avatar', 'position')
                        ->where('book_user.owner_id', $this->user->id);
                    $query->orderBy('book_user.created_at', 'ASC')->limit(1);
                }
            ]))
            ->getBookByOffice($officeId)
            ->orderBy('created_at', 'DESC')
            ->paginate(config('paginate.default'));

        return $books;
    }

    public function getBookApproveDetail($bookId, $dataSelect = ['*'], $with = [])
    {
        return $this->user->owners()->where('book_id', $bookId)
            ->select($dataSelect)
            ->with(array_merge($with, [
                'usersWaiting' => function($query) {
                    $query->select('id', 'name', 'avatar', 'position', 'email')
                        ->where('book_user.owner_id', $this->user->id);
                    $query->orderBy('book_user.created_at', 'ASC');
                },
                'usersReturning' => function($query) {
                    $query->select('id', 'name', 'avatar', 'position', 'email')
                        ->where('book_user.owner_id', $this->user->id);
                    $query->orderBy('book_user.created_at', 'ASC')->limit(1);
                },
                'usersReading' => function($query) {
                    $query->select('id', 'name', 'avatar', 'position', 'email')
                        ->where('book_user.owner_id', $this->user->id);
                    $query->orderBy('book_user.created_at', 'ASC')->limit(1);
                },
                'usersReturned' => function($query) {
                    $query->select('id', 'name', 'avatar', 'position', 'email')
                        ->where('book_user.owner_id', $this->user->id);
                    $query->orderBy('book_user.created_at', 'ASC');
                }
            ]))
            ->firstOrFail();
    }

    public function getNotifications()
    {
        $notification = app(Notification::class)
            ->with([
                'book',
                'userSend' => function($query) {
                    $query->select($this->userSelect);
                },
                'userReceive' => function($query) {
                    $query->select($this->userSelect);
                }
            ])
            ->where('user_receive_id', $this->user->id)
            ->orderBy('created_at', 'DESC')
            ->paginate(config('paginate.default'));

        $notificationFollow = app(Notification::class)
            ->with([
                'book',
                'userSend' => function($query) {
                    $query->select($this->userSelect);
                },
                'userReceive' => function($query) {
                    $query->select($this->userSelect);
                }
            ])
            ->orWhereIn('user_send_id', function($query){
                $query->select('following_id')
                ->from('user_follow')
                ->where('follower_id', $this->user->id)
                ->get();
            })
            ->orderBy('created_at', 'DESC')
            ->paginate(config('paginate.default'));

        return compact('notification', 'notificationFollow');
    }

    public function getNotificationsDropdown()
    {
        $data = app(Notification::class)
            ->with([
                'book',
                'userSend' => function($query) {
                    $query->select($this->userSelect);
                },
                'userReceive' => function($query) {
                    $query->select($this->userSelect);
                }
            ])
            ->where('user_receive_id', $this->user->id)
            ->orderBy('created_at', 'DESC')
            ->limit(16)->get();

        return compact('data');
    }

    public function followOrUnfollow($userId)
    {
        if ($this->user->id === $userId) {
            throw new ActionException('can_not_follow_yourself');
        }
        $follow = app(UserFollow::class)->where('following_id', $userId)
            ->where('follower_id', $this->user->id)
            ->first();
        if ($follow) {
            $follow->delete();
        } else {
            app(UserFollow::class)->create([
                'following_id' => $userId,
                'follower_id' => $this->user->id,
            ]);
        }
    }

    public function getFollowInfo($id, $dataSelect = ['*'], $with = [])
    {
        $follower_id = $this->model()->findOrFail($id)->usersFollowing->pluck('follower_id');
        $following_id = $this->model()->findOrFail($id)->usersFollower->pluck('following_id');
        $followedBy = $this->model()->select('id', 'name', 'avatar')->whereIn('id', $follower_id)->orderBy('name')->get();
        $following = $this->model()->select('id', 'name', 'avatar')->whereIn('id', $following_id)->orderBy('name')->get();
        $countFollowed = $followedBy->count();
        $countFollowing = $following->count();
        $isFollow = app(UserFollow::class)
            ->where('following_id', $id)
            ->where('follower_id', $this->user->id)
            ->first();
        if (!$isFollow) {
            $isFollow = false;
        } else {
            $isFollow = true;
        }

        return compact('followedBy', 'following', 'isFollow', 'countFollowed', 'countFollowing');
    }

    public function updateViewNotifications($notificationId)
    {
        $update_view = app(Notification::class)->findOrFail($notificationId)->update(['viewed' => config('model.notification.viewed')]);
    }

    public function countNotificationNotView()
    {
        $countNoSeen = app(Notification::class)->where('user_receive_id', $this->user->id)->where('viewed', config('model.notification.not_view'))->count();

        return ['count' => $countNoSeen];
    }

    public function getFavoriteCategory($id)
    {
        $user = $this->model()->findOrFail($id);
        $tags = explode(",", $user['tags']);
        $categories = app(Category::class)->whereIn('id', $tags)->get();

        return $categories;
    }

    public function updateViewNotificationsAll()
    {
        $update_view = app(Notification::class)->where('viewed', config('model.notification.not_view'))->update(['viewed' => config('model.notification.viewed')]);
    }

    public function getWaitingApproveEditBook($dataSelect = ['*'])
    {
        return app(UpdateBook::class)
            ->select($dataSelect)
            ->with([
                'category',
                'office',
                'updateMedia',
                'userRequest',
                'currentBookInfo' => function($query) {
                    $query->with(['category', 'office', 'media']);
                }
            ])
            ->orderBy('created_at', 'ASC')
            ->paginate(config('paginate.default'));
    }

    public function countRecord()
    {
        return $this->model()->count();
    }

    public function getByPage($dataSelect = ['*'], $withRelation = [])
    {
        return $this->model()
            ->select($dataSelect)
            ->with(array_merge($withRelation, ['office']))
            ->withCount('owners')
            ->orderBy('created_at', 'ASC')
            ->paginate(config('paginate.default'));
    }

    public function search($data, $dataSelect = ['*'], $withRelation = [])
    {
        Paginator::currentPageResolver(function() use ($data) {
            return $data['page'];
        });

        if ($data['type'] == config('model.filter_user.by_email')) {
            $query = $this->model()
                ->where('email', 'like', '%' . $data['key'] . '%');
        } else {
            $query = $this->model()
                ->where('employee_code', 'like', '%' . $data['key'] . '%');
        }

        return $query->select($dataSelect)
            ->with(array_merge($withRelation, ['office']))
            ->withCount('owners')
            ->orderBy('created_at', 'ASC')
            ->paginate(config('paginate.default'));
    }
}
