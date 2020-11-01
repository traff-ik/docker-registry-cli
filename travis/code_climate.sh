#!/bin/bash
curl -L https://codeclimate.com/downloads/test-reporter/test-reporter-latest-linux-amd64 > ./cc-test-reporter
chmod +x ./cc-test-reporter
docker cp cc-test-reporter registry-cli:/usr/lib/registry/cc-test-reporter
