<?php

namespace App\Http\Controllers\Api;

use App\Contracts\Repositories\BookRepository;
use App\Contracts\Repositories\CategoryRepository;
use App\Contracts\Repositories\OfficeRepository;
use App\Exceptions\Api\NotFoundException;
use App\Http\Requests\Api\Book\ApproveRequest;
use App\Http\Requests\Api\Book\BookFilteredByCategoryRequest;
use App\Http\Requests\Api\Book\BookFilterRequest;
use App\Http\Requests\Api\Book\FilterBookInCategoryRequest;
use App\Http\Requests\Api\Book\SearchRequest;
use App\Exceptions\Api\ActionException;
use App\Http\Requests\Api\Book\IndexRequest;
use App\Http\Requests\Api\Book\BookingRequest;
use App\Http\Requests\Api\Book\ReviewRequest;
use App\Http\Requests\Api\Book\StoreRequest;
use App\Contracts\Repositories\MediaRepository;
use App\Http\Requests\Api\Book\UpdateRequest;
use App\Http\Requests\Api\Book\UploadMediaRequest;
use Illuminate\Http\Request;
use App\Events\NotificationHandler;
use App\Contracts\Repositories\UserRepository;
use App\Eloquent\User;

class BookController extends ApiController
{
    protected $select = [
        'id',
        'title',
        'description',
        'author',
        'publish_date',
        'total_page',
        'count_view',
        'category_id',
        'office_id',
        'avg_star',
    ];

    protected $ownerSelect = [
        'id',
        'name',
        'avatar',
        'position'
    ];

    protected $imageSelect = [
        'path',
        'size',
        'thumb_path',
        'target_id',
        'target_type',
    ];

    protected $categorySelect = [
        'id',
        'name',
    ];

    protected $officeSelect = [
        'id',
        'name',
    ];

    protected $counter;

    public function __construct(BookRepository $repository)
    {
        parent::__construct($repository);
    }

    public function index(IndexRequest $request)
    {
        $field = $request->input('field');
        $officeId = $request->get('office_id');

        if (!$field) {
            throw new ActionException;
        }

        $relations = [
            'owners' => function ($q) {
                $q->select($this->ownerSelect);
            },
            'image' => function ($q) {
                $q->select($this->imageSelect);
            },
            'office' => function ($q) {
                $q->select($this->officeSelect);
            }
        ];

        return $this->getData(function () use ($relations, $field, $officeId) {
            $data = $this->repository->getBooksByFields($relations, $this->select, $field, [], $officeId);

            $this->compacts['items'] = $this->reFormatPaginate($data);
        });
    }

    public function show($id)
    {
        $this->compacts['item'] = $this->repository->show($id);

        return $this->jsonRender();
    }

    public function store(StoreRequest $request, MediaRepository $mediaRepository)
    {
        $data = $request->all();

        return $this->doAction(function () use ($data, $mediaRepository) {
            $this->compacts['item'] = $this->repository->store($data, $mediaRepository);
        });
    }

    public function requestUpdate(UpdateRequest $request, $id, MediaRepository $mediaRepository)
    {
        $data = $request->all();

        return $this->doAction(function () use ($data, $id, $mediaRepository) {
            $book = $this->repository->findOrFail($id);
            $this->before('update', $book);

            $this->repository->requestUpdateBook($data, $book, $mediaRepository);
        }, __FUNCTION__);
    }

    public function approveRequestUpdate(Request $request, $updateBookId)
    {
        return $this->doAction(function () use ($updateBookId) {

            $this->repository->approveRequestUpdateBook($updateBookId);
        }, __FUNCTION__);
    }

    public function deleteRequestUpdate($updateBookId)
    {
        return $this->doAction(function () use ($updateBookId) {

            $this->repository->deleteRequestUpdateBook($updateBookId);
        }, __FUNCTION__);
    }

    public function increaseView($id)
    {
        return $this->doAction(function () use ($id) {
            $book = $this->repository->findOrFail($id);

            $this->repository->increaseView($book);
        }, __FUNCTION__);
    }

    public function destroy($id)
    {
        return $this->doAction(function () use ($id) {
            $book = $this->repository->findOrFail($id);
            $this->before('delete', $book);

            $this->repository->destroy($book);
        }, __FUNCTION__);
    }

    public function search(SearchRequest $request)
    {
        $data = $request->all();
        $officeId = $request->get('office_id');

        return $this->getData(function () use ($data, $officeId) {
            $this->compacts['items'] = $this->reFormatPaginate(
                $this->repository->getDataSearch($data, ['image', 'category', 'office', 'owners'], $this->select, $officeId)
            );
        });
    }

    public function booking(BookingRequest $request)
    {
        $data = $request->all();

        return $this->doAction(function () use ($data) {
            $book = $this->repository->findOrfail($data['item']['book_id']);

            $this->repository->booking($book, $data);
        });
    }

    public function approve($bookId, ApproveRequest $request)
    {
        $data = $request->all();

        return $this->doAction(function () use ($data, $bookId) {
            $book = $this->repository->findOrfail($bookId);
            $this->before('update', $book);

            $this->repository->approve($book, $data['item']);
        });
    }

    public function sortBy()
    {
        $this->compacts['items'] = config('model.condition_sort_book');

        return $this->jsonRender();
    }

    public function review(ReviewRequest $request, $bookId)
    {
        $data = $request->item;
        return $this->doAction(function () use ($bookId, $data) {
            $this->repository->review($bookId, $data);
        });
    }

    public function reviewNew(ReviewRequest $request, $bookId)
    {
        $data = $request->item;
        return $this->doAction(function () use ($bookId, $data) {
            $this->repository->reviewNew($bookId, $data);
        });
    }

    public function filter(BookFilterRequest $request)
    {
        $field = $request->input('field');
        $officeId = $request->get('office_id');

        $input = $request->all();

        $relations = [
            'owners' => function ($q) {
                $q->select($this->ownerSelect);
            },
            'image' => function ($q) {
                $q->select($this->imageSelect);
            },
            'category' => function ($q) {
                $q->select($this->categorySelect);
            },
            'office' => function ($q) {
                $q->select($this->officeSelect);
            }
        ];

        return $this->getData(function () use ($relations, $field, $input, $officeId) {
            $data = $this->repository->getBooksByFields($relations, $this->select, $field, $input, $officeId);

            $this->compacts['items'] = $this->reFormatPaginate($data);
        });
    }

    public function category($categoryId, CategoryRepository $categoryRepository, Request $request)
    {
        $category = $categoryRepository->find($categoryId);
        $officeId = $request->get('office_id');

        if (!$category) {
            throw new NotFoundException;
        }

        $relations = [
            'owners' => function ($q) {
                $q->select($this->ownerSelect);
            },
            'image' => function ($q) {
                $q->select($this->imageSelect);
            },
            'office' => function ($q) {
                $q->select($this->officeSelect);
            }
        ];

        return $this->getData(function () use ($relations, $category, $officeId) {
            $bookCategory = $this->repository->getBookByCategory($category->id, $this->select, $relations, $officeId);
            $currentPage = $bookCategory->currentPage();

            $this->compacts['items'] = [
                'total' => $bookCategory->total(),
                'per_page' => $bookCategory->perPage(),
                'current_page' => $currentPage,
                'next_page' => ($bookCategory->lastPage() > $currentPage) ? $currentPage + 1 : null,
                'prev_page' => $currentPage - 1 ?: null,
                'category' => [
                    'id' => $category->id,
                    'name' => $category->name,
                    'data' => $bookCategory->items(),
                ]
            ];
        });
    }

    public function filterCategory($categoryId, BookFilteredByCategoryRequest $request, CategoryRepository $categoryRepository)
    {
        $category = $categoryRepository->find($categoryId);
        $officeId = $request->get('office_id');

        $input = $request->all();

        if (!$category) {
            throw new NotFoundException;
        }

        $relations = [
            'owners' => function ($q) {
                $q->select($this->ownerSelect);
            },
            'image' => function ($q) {
                $q->select($this->imageSelect);
            },
            'office' => function ($q) {
                $q->select($this->officeSelect);
            }
        ];

        return $this->getData(function () use ($relations, $category, $input, $officeId) {
            $bookCategory = $this->repository->getBookFilteredByCategory($category->id, $input, $this->select, $relations, $officeId);
            $currentPage = $bookCategory->currentPage();

            $this->compacts['items'] = [
                'total' => $bookCategory->total(),
                'per_page' => $bookCategory->perPage(),
                'current_page' => $currentPage,
                'next_page' => ($bookCategory->lastPage() > $currentPage) ? $currentPage + 1 : null,
                'prev_page' => $currentPage - 1 ?: null,
                'category' => [
                    'id' => $category->id,
                    'name' => $category->name,
                    'data' => $bookCategory->items(),
                ]
            ];
        });
    }

    public function addOwner($id)
    {
        return $this->requestAction(function () use ($id) {
            $this->repository->addOwner($id);
        });
    }

    public function removeOwner($id)
    {
        return $this->doAction(function () use ($id) {
            $book = $this->repository->findOrFail($id);
            $this->before('delete', $book);

            $this->repository->removeOwner($book);
        });
    }

    public function uploadMedia(UploadMediaRequest $request, MediaRepository $mediaRepository)
    {
        $data = $request->all();

        return $this->doAction(function () use ($data, $mediaRepository) {
            $book = $this->repository->findOrFail($data['book_id']);
            $this->before('update', $book);

            $this->compacts['item'] = $this->repository->uploadMedia($book, $data, $mediaRepository);
        }, __FUNCTION__);
    }

    public function office($officeId, OfficeRepository $officeRepository)
    {
        $office = $officeRepository->find($officeId);

        if (!$office) {
            throw new NotFoundException;
        }

        $relations = [
            'owners' => function ($q) {
                $q->select($this->ownerSelect);
            },
            'image' => function ($q) {
                $q->select($this->imageSelect);
            },
            'category' => function ($q) {
                $q->select($this->categorySelect);
            }
        ];

        return $this->getData(function () use ($relations, $office) {
            $bookOffice = $this->repository->getBookByOffice($office->id, $this->select, $relations);
            $currentPage = $bookOffice->currentPage();

            $this->compacts['item'] = [
                'total' => $bookOffice->total(),
                'per_page' => $bookOffice->perPage(),
                'current_page' => $currentPage,
                'next_page' => ($bookOffice->lastPage() > $currentPage) ? $currentPage + 1 : null,
                'prev_page' => $currentPage - 1 ?: null,
                'office' => [
                    'id' => $office->id,
                    'name' => $office->name,
                    'data' => $bookOffice->items(),
                ]
            ];
        });
    }

    public function getTotalBook()
    {
        return $this->getData(function() {
            $this->compacts['item'] = $this->repository->countRecord();
        });
    }
}
