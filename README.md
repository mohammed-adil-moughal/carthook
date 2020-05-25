# Advanced/Practical

## Implementation
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

## Improvements
1) Move all logic to a service and have controllers explicitly receive requests and dispatch responses this is usefull to have a structured folder structure setup for large scale mamangement and better development experience
2) Move caching to a separate cron  (maybe a cron server as well) and have it run on regular intervals as opposed to on a specific request to the endpoint
3) Improve caching to more intelligent and not just truncate and insert cause if no change to data then we might not need to update records can improve this to avoid extra database calls
4) Implement caching on routes Cloudflare and bust cache when a valid update happens(tied to intelligent caching)
5) Add versioning to API endpoints for further extension


# Basic CS

A) Design a SQL database to store NBA players, teams and games (column and table contents are all up to you). Users mostly query game results by date and team name. The second most frequent query is players statistics by player name.
```
Table players

PK id 
string name
string desciption
string weight
string height

FK team_fk (foreign key to the Teams Table)
DateTime date_created
DateTime date_modified


Table teams

PK id
PK name
DateTime date_created
DateTime date_modified

Table games

PK id
FK home_team
FK away_team
DateTime date_played
DateTime date_created
DateTime date_modified

```


B) How would you find files that begin with "0aH" and delete them given a folder (with subfolders)? Assume there are many files in the folder.
linux comand
```
find -type f -name '*OaH*' -exec rm {}
```

C) Write a function that sorts 11 small numbers (<100) as fast as possible. Estimate how long it would take to execute that function 10 Billion (10^10) times on a normal machine?
```
we could user phps inbuilt sort function
$numbers = [9,8,2,1,3,5,10,11,12,29,49,59,20,34,23,45];
sort($numbers);
```
BEST algorithim for sorting will be Quick sort but takes abit of time to implement so will do bubble sort O(n^2) very in efficient
```
function bubbleSort($arr) {
  $length = count($arr);
  for ( $i = 0; $i < $length; $i++ ) {
    for ( $j = 0; $j < $length - 1; $j++ ) {
      if ( $arr[ $j ] > $arr[ $j + 1 ] ) {
        $tmp            = $arr[ $j + 1 ];
        $arr[ $j + 1 ]  = $arr[ $j ];
        $arr[ $j ]      = $tmp;
      } 
    } 
  } 
  return $arr;
}

```
The time to execute this will increment expoentially as it has two for loops running

D) Write a function that sorts 10000 powers (a^b) where a and b are random numbers between 100 and 10000? Estimate how long it would take on your machine?

Quick sort Algorithim
```
function partition(&$arr,$leftIndex,$rightIndex)
{
    $pivot=$arr[($leftIndex+$rightIndex)/2]; //find midpoint of array
     
    while ($leftIndex <= $rightIndex) //continue till rightindex is greater than left
    {        
        while ($arr[$leftIndex] < $pivot)             
                $leftIndex++;//increment counter if element in index is less than pivot
        while ($arr[$rightIndex] > $pivot)
                $rightIndex--;//increment counter if element in index is greater than pivot
        if ($leftIndex <= $rightIndex) {  
                $temp = $arr[$leftIndex]; //temp variable to hold left
                $arr[$leftIndex] = $arr[$rightIndex]; // assign right element to left
                $arr[$rightIndex] = $temp; //then assign the right to the temp variable
                $leftIndex++; //increment counter
                $rightIndex--; // decrement counter
        }
    }
    return $leftIndex;
}
 
function quickSort(&$arr, $leftIndex, $rightIndex)
{
    $index = partition($arr,$leftIndex,$rightIndex);
    if ($leftIndex < $index - 1)
        quickSort($arr, $leftIndex, $index - 1);
    if ($index < $rightIndex)
        quickSort($arr, $index, $rightIndex);
}
```
