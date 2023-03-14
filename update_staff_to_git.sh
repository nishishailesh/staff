echo 'give username:'
read u

mysqldump -h127.0.0.1 -d -u$u -p staff > staff_blank.sql 
git add *
git commit
git push https://github.com/nishishailesh/staff main
