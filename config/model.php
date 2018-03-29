<?php

return [
    'book' => [
        'status' => [
            'unavailable' => 'unavailable',
            'available' => 'available',
        ],
        'fields' => [
            'title',
            'description',
            'author',
            'publish_date',
            'total_page',
            'avg_star',
            'code',
            'count_view',
            'status',
            'created_at'
        ]
    ],
    'book_user' => [
        'status' => [
            'waiting' => 1,
            'reading' => 2,
            'returning' => 3,
            'returned' => 4,
        ]
    ],
    'book_user_status_cancel' => 5,
    'filter_books' => [
        'view' => [
            'key' => 'view',
            'field' => 'count_view',
            'title' => translate('title_key.view')
        ],
        'waiting' => [
            'key' => 'waiting',
            'field' => '',
            'title' => translate('title_key.waiting')
        ],
        'rating' => [
            'key' => 'rating',
            'field' => 'avg_star',
            'title' => translate('title_key.rating')
        ],
        'latest' => [
            'key' => 'latest',
            'field' => 'created_at',
            'title' => translate('title_key.latest')
        ],
        'read' => [
            'key' => 'read',
            'field' => '',
            'title' => translate('title_key.read')
        ]
    ],
    'condition_sort_book' => [
        ['text' => 'Title', 'field' => 'title'],
        ['text' => 'View', 'field' => 'count_view'],
        ['text' => 'Star', 'field' => 'avg_star'],
        ['text' => 'Publish date', 'field' => 'publish_date'],
        ['text' => 'Author', 'field' => 'author'],
        ['text' => 'Created at', 'field' => 'created_at'],
    ],
    'filter_type' => [
        'category', 'office'
    ],
    'sort_type' => [
        'desc', 'asc'
    ],
    'media_type' => [
        'image' => 0,
        'video' => 1,
    ],
    'user_sharing_book' => 'sharing',
    'user_reviewed_book' => 'reviewed',
    'media' => [
        'type' => [
            'avatar_book' => 1,
            'not_avatar_book' => 0
        ],
    ],
    'notification' => [
        'approve_waiting' => 'approve_waiting',
        'unapprove_waiting' => 'unapprove_waiting',
        'approve_returning' => 'approve_returning',
        'add_owner' => 'add_owner',
        'remove_owner' => 'remove_owner',
        'review' => 'review',
        'waiting' => 'waiting',
        'cancel' =>' cancel',
        'returning' => 'returning',
        'returned' => 'returned',
        'add_book'=> '0',
        'remove_book'=> '0',
        'viewed' => true,
        'not_view' => false,
        'admin' => [
            'request_edit_book' => 'request_edit_book',
            'approve_request_update_book' => 'approve_edit_book',
            'delete_request_update_book' => 'delete_edit_book',
        ],
    ],
    'review_messeges' => [
        'can_not_vote' => 'can_not_vote',
        'revote_success' => 'revote_success',
        'vote_success' => 'vote_success'
    ],
    'request_vote' => [
        'up_vote' => 2,
        'down_vote' => 1
    ],
    'filter_user' => [
        'by_staff_code' => 1,
        'by_email' => 2
    ],
];
