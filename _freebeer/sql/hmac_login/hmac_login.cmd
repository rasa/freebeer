mysqladmin drop      hmac_login
mysqladmin create    hmac_login
mysql                hmac_login <hmac_login_mysql.sql
sqlplus scott/tiger @hmac_login_oracle.sql
sqlite               hmac_login_sqlite.sql
