# GenDiff (php-project-48)

### Hexlet tests and linter status:

[![Actions Status](https://github.com/sleeplesspony/php-project-48/actions/workflows/hexlet-check.yml/badge.svg)](https://github.com/sleeplesspony/php-project-48/actions) [![ci-tests](https://github.com/sleeplesspony/php-project-48/actions/workflows/wofkflow.yml/badge.svg)](https://github.com/sleeplesspony/php-project-48/actions/workflows/wofkflow.yml) [![Maintainability Rating](https://sonarcloud.io/api/project_badges/measure?project=sleeplesspony_php-project-48&metric=sqale_rating)](https://sonarcloud.io/summary/new_code?id=sleeplesspony_php-project-48) [![Coverage](https://sonarcloud.io/api/project_badges/measure?project=sleeplesspony_php-project-48&metric=coverage)](https://sonarcloud.io/summary/new_code?id=sleeplesspony_php-project-48)

### About

GenDiff is a command-line utility for comparing two configuration files and generating a difference report. It supports comparing files in JSON and YAML formats and can output the diff in three formats: Stylish, Plain, and JSON.

### Prerequisites

* Linux, Macos, WSL
* PHP >=8.2
* Make
* Git
* Composer

### Setup

Setup [SSH](https://docs.github.com/en/authentication/connecting-to-github-with-ssh) before clone:

```bash
git clone git@github.com:sleeplesspony/php-project-48.git
cd php-project-48

make install
```

### Help
```bash
bin/gendiff -h
```

### See GenDiff in action

Generation diff for plain Json files (Step 4):
https://asciinema.org/a/FnGWnM7hrFzOpYK9xdiBIZZ0X

Generation diff for plain Json and Yaml files (Step 6):
https://asciinema.org/a/kmywGljwWpocSjd6yrVrZsDgD

Stylish diff generation (Step 7):
https://asciinema.org/a/xXKhq2s1BRLFqGfxqevSXSVEj

Plain diff generation (Step 8):
https://asciinema.org/a/J5LhW4YX6EvpPzWnL5YfSCv5P

Json diff generation (Step 9):
https://asciinema.org/a/FP2sqTjl3VG3EgcIfatVHsQUF

