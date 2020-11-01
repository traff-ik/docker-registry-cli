#!/bin/bash
echo "$DOCKER_PASSWORD" | docker login -u "$DOCKER_USERNAME" --password-stdin

docker tag "$TRAVIS_REPO_SLUG" "$DOCKER_USERNAME"/registry-cli:latest

docker push "$DOCKER_USERNAME"/registry-cli:latest

