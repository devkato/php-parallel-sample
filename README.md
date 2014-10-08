# Parallel-Processing example with PHP and Redis

## Lesson

See and understand publisher/subscriber/aggreator/task.php with my lecture.

## How to use

### launch aggregator

```shell
php aggregator.php
# => waiting result...
```

### launch subscriber

specify channel number(1, 2, etc) as first argument.

```shell
php subscriber.php 1
# => subscribe chan-1
```

### publish

specify number of process(1, 2, etc) as first argument.

```shell
php publisher.php 2
# => ...
```

**!!caution!!** you must launch more subscribers than number of process specified.

## Practice

create simple parallel-rawler

- publisher.php accept keywords array and set each keywrod to each subscribing process.
- Each process send search query to Rakuten Auction Search API( http://webservice.rakuten.co.jp/api/auctionitemsearch/ )
- Aggregate each API results in aggregate_callback into one JSON data, and save it on disk by specific name with some prefix or suffix relevant to keywords publisher.php got.
