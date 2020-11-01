#!/bin/bash
docker network create --driver bridge registry

docker run -d -p 5000:5000 --network registry --name registry \
    -v "$(pwd)"/docker/registry/config.yml:/etc/docker/registry/config.yml \
    registry:2

docker run -d --name registry-cli --network registry \
    --entrypoint tail \
    -v "$(pwd)"/tests:/usr/lib/registry/tests \
    -v "$(pwd)"/phpunit.xml.dist:/usr/lib/registry/phpunit.xml \
    -v "$(pwd)"/phpcs.xml.dist:/usr/lib/registry/phpcs.xml \
    -e CC_TEST_REPORTER_ID="${CC_REPORTER_ID}" \
    -e TRAVIS="${TRAVIS}" \
    -e TRAVIS_PULL_REQUEST="${TRAVIS_PULL_REQUEST}" \
    -e TRAVIS_JOB_ID="${TRAVIS_JOB_ID}" \
    -e TRAVIS_BRANCH="${TRAVIS_BRANCH}" \
    "${TRAVIS_REPO_SLUG}" -f /dev/null
