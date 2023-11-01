#!/bin/sh

if [ ! -f .env.local ]
then
  export "$(cat .env.local | xargs)"
fi


rsync compose.* root@bera.watch:app/
rsync devops/deploy.sh root@bera.watch:app/
ssh root@bera.watch 'cd app/ ; ./deploy.sh'
