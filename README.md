# lamp-stack-portfolio
Learning a new stack to host websites with, test different backends, ect. 


1. Clone the repository
2. Create an .env file as below in the root of your src

3. Start Docker:
   docker-compose up -d
   
# SAMPLE .ENV FILE
## Database Configuration new line after each parameter

DB_HOST=mysql
DB_USER=your_db_user
DB_PASS=your_db_password
DB_NAME=your_db_name
DB_ROOT_PASS=your_root_password
APP_ENV=development
APP_DEBUG=true
