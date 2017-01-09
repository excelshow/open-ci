#!/usr/bin/env bash

projectPath=$(cd "$(dirname $(dirname $(dirname $(dirname "$0"))))"; pwd)

sphinxConfigFilePath=${projectPath}"/application/controllers/sphinx/conf/sphinx.conf"

echo ${sphinxConfigFilePath}

SEARCHD=/usr/bin/searchd

${SEARCHD} -c ${sphinxConfigFilePath} --stop
sleep 1s
${SEARCHD} -c ${sphinxConfigFilePath}
