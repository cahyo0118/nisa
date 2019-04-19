APP_NAME={{ $project->name }}
APP_ENV=local
APP_KEY=base64:TWAXPE3uQfcxDRC4VbH+wH6zGOC5DuLsyQSiFVLrn04=
APP_DEBUG=true
APP_URL=http://localhost

LOG_CHANNEL=stack

ITEM_PER_PAGE={{ $project->item_per_page }}

GOOGLE_CLIENT_ID=

DB_CONNECTION={{ $project->db_connection }}
DB_HOST={{ $project->db_host }}
DB_PORT={{ $project->db_port }}
DB_DATABASE={{ $project->db_name }}
DB_USERNAME={{ $project->db_username }}
DB_PASSWORD={{ $project->db_password }}

BROADCAST_DRIVER=log
CACHE_DRIVER=file
QUEUE_CONNECTION=sync
SESSION_DRIVER=file
SESSION_LIFETIME=120

REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379

MAIL_DRIVER={{ $project->mail_driver }}
MAIL_HOST={{ $project->mail_host }}
MAIL_PORT={{ $project->mail_port }}
MAIL_USERNAME={{ $project->mail_username }}
MAIL_PASSWORD={{ $project->mail_password }}
MAIL_ENCRYPTION={{ $project->mail_encryption }}

PUSHER_APP_ID=
PUSHER_APP_KEY=
PUSHER_APP_SECRET=
PUSHER_APP_CLUSTER=mt1

MIX_PUSHER_APP_KEY="${PUSHER_APP_KEY}"
MIX_PUSHER_APP_CLUSTER="${PUSHER_APP_CLUSTER}"
