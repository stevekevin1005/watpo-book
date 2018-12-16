import { init,end,api_service_provider_time} from "./function";
let config = {
  host    : 'localhost',
  user    : 'root',
  password: 'secret',
  database: 'test'
};

const main = async () =>{
  let old = new Date();  // measure time

  init(config);
  let result =  await api_service_provider_time("2018-11-26",1,null,null,2,0,true);
  console.log(result);
  result =  await api_service_provider_time("2018-11-26",2,null,null,2,0,true);
  console.log(result);
  result =  await api_service_provider_time("2018-11-25",1,null,null,2,0,true);
  console.log(result);
  result =  await api_service_provider_time("2018-11-25",2,null,null,2,0,true);
  console.log(result);
  end();

  let ne = new Date(); // measure time
  console.log( (ne.getTime()-old.getTime())/1000); // result
  process.exit()
};

main();

//end();