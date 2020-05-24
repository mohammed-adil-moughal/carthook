#Advanced/Practical

##Implementation
1) Created Cache layer to Database -> this will be used to temporary cache the results with a time to live entry in the database and when the time to live expires we request a fetch and truncate the related tables

2) Created Tables
```
Users -> to hold the users
    'name', 'email', 'username', 'phone', 'website'
Posts -> to hold the Posts 
    'userId', 'title', 'body'
Comments -> to hold the Comments
    'postId', 'name', 'email', 'body'
Cache -> will hold cache 
   'table_name', 'time_to_live' 
```
requirements including the names of tables which can be extended to functionality i.e for comments to have comments and Id

3) Implemented Models with fillables as per API endpoint for easy inserts

4) Added Endpoints


```
Fetch post by title -> localhost:8000/api/posts?title=qui
Fetch all posts -> localhost:8000/api/posts
Fetch all comments of a Post -> localhost:8000/api/posts/1/comments
```

```
Fetch Users -> localhost:8000/api/users?email=question@com.com
Fetch Users -> localhost:8000/api/users/1
Fetch Users Posts -> localhost:8000/api/users/1/posts
Fetch Users Single Posts -> localhost:8000/api/users/1/posts/1
```

##Improvements
1) Move all logic to a service and have controllers explicitly receive requests and dispatch responses this is usefull to have a structured folder structure setup for large scale mamangement and better development experience
2) Move caching to a separate cron  (maybe a cron server as well) and have it run on regular intervals as opposed to on a specific request to the endpoint
3) Improve caching to more intelligent and not just truncate and insert cause if no change to data then we might not need to update records can improve this to avoid extra database calls
4) Implement caching on routes Cloudflare and bust cache when a valid update happens(tied to intelligent caching)
5) Add versioning to API endpoints for further extension


