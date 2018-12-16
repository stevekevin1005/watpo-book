var mysql      = require('mysql');
var connection = mysql.createConnection({
  host     : 'localhost',
  user     : 'root',
  password : '',
  database : 'watpo-book'
});
 
connection.connect();
 
let result = [];
connection.query('SELECT * from Shop', function (error, results, fields) {
  if (error) throw error;
  result.push(results);
});
connection.query('SELECT * from Shop', function (error, results, fields) {
  if (error) throw error;
  result.push(results);
});
while(1) {
	// sleep(100);
	console.log(result);
	if(result.length > 3) {
		console.log(result);
		break;
	}
}