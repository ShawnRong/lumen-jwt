<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\Activity;
use App\Models\User;

class ProfilesController
{

    /**
     * @api {get} /profiles/{user} Get user activity records
     * @apiDescription Get user activity records
     * @apiGroup Profile
     * @apiPermission none
     * @apiVersion 0.1.0
     * @apiSuccessExample {json} Success-Response:
     *     HTTP/1.1 201 OK
     *
     *      {
     *          "profileUser": {
     *              "id": 1,
     *              "name": "testUsers",
     *              "email": "admin@123.com",
     *              "created_at": "2017-05-18 19:27:40",
     *              "updated_at": "2017-07-12 04:00:27"
     *          },
     *          "activities": {
     *              "2017-07-11": [
     *              {
     *                  "id": 2,
     *                  "user_id": 1,
     *                  "subject_id": 8,
     *                  "subject_type": "App\\Models\\Reply",
     *                  "type": "created_reply",
     *                  "created_at": "2017-07-11 06:24:01",
     *                  "subject": null
     *              },
     *              {
     *                  "id": 1,
     *                  "user_id": 1,
     *                  "subject_id": 31,
     *                  "subject_type": "App\\Models\\Thread",
     *                  "type": "created_thread",
     *                  "created_at": "2017-07-11 06:22:30",
     *                  "subject": {
     *                  "id": 31,
     *                  "user_id": 1,
     *                  "channel_id": 1,
     *                  "replies_count": 0,
     *                  "title": "active_test",
     *                  "body": "active_test_body",
     *                  "created_at": "2017-07-11 06:22:29"
     *              }
     *          }
     *          ]
     *          }
     *      }
     */
    public function show(User $user)
    {
        return [
            'profileUser' => $user,
            'activities' => Activity::feed($user)
        ];
    }
}
