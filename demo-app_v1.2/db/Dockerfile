FROM mysql:latest
ENV MYSQL_ROOT_PASSWORD="laravelpassword"
COPY sql/init.sql /docker-entrypoint-initdb.d/
EXPOSE 3306
CMD ["mysqld"]
