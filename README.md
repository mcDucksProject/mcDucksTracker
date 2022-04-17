# McDuck's Tracker

What if Mc Duck had a crypto tracker?

#Install
```bash
 docker build docker/composer-install -t composer-install && docker run --rm -u "$(id -u):$(id -g)" -v $(pwd):/opt -w /opt composer-install composer install --ignore-platform-reqs && docker rmi composer-install
