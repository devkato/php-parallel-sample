#!/bin/sh
sudo yum install -y redis php-redis
sudo /sbin/service redis start
git clone https://github.com/devkato/php-parallel-sample.git
cd php-parallel-sample
wget https://s3-ap-northeast-1.amazonaws.com/aucfan-tuningathon/binary_data/bin.dat.small -O data/bin.dat.small
wget https://s3-ap-northeast-1.amazonaws.com/aucfan-tuningathon/binary_data/bin.dat.medium -O data/bin.dat.medium
wget https://s3-ap-northeast-1.amazonaws.com/aucfan-tuningathon/binary_data/bin.dat.large -O data/bin.dat.large

