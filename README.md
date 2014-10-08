# Parallel-Processing example with PHP and Redis

## Lesson

See and understand publisher/subscriber/aggreator/task.php with my lecture.


## Practice

create simple parallel-rawler

- publisher.php accept keywords array and set each keywrod to each subscribing process.
- Each process send search query to Rakuten Auction Search API( http://webservice.rakuten.co.jp/api/auctionitemsearch/ )
- Aggregate each API results in aggregate_callback into one JSON data, and save it on disk by specific name with some prefix or suffix relevant to keywords publisher.php got.
