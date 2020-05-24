Implementation
1) Created Cache layer to Database
2) Implemented Models with fillables as per API endpoint for easy inserts
3) Added Endpoints



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

Improvements
1) Move all logic to a service and have controllers explicitly receive requests and dispatch responses
2) Move caching to a separate cron  (maybe a cron server as well) and have it run on regular intervals
3) Improve caching to more intelligent and not just truncate and insert
4) Implement caching on routes Cloudflare and bust cache when a valid update happens(tied to intelligent caching)


