mysqladmin drop      adodb_sessions
mysqladmin create    adodb_sessions
mysql                adodb_sessions <adodb_sessions_mysql.sql
sqlplus scott/tiger @adodb_sessions_oracle_clob.sql
sqlite               adodb_sessions_sqlite.sql
