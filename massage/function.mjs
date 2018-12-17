

import mysql from 'mysql';
import array_diff from  'locutus/php/array/array_diff';
import empty from  'locutus/php/var/empty';
import moment from 'moment'
let connection;
let count = 0;

const init = (config)=>{
    connection = mysql.createConnection(config);
  };

const end = () => {
  connection.end();
};

const query = (sql, parameter = true) => {
  return new Promise((resolve, reject) => {
    let result;
    let la = connection.query(sql, parameter, (error, results, fields) => {
      if (error) {
        console.warn(error);
        reject(error.message);
      }
      resolve(results);
    });
    console.log(count++,la.sql )
  });
};


const api_service_provider_time = async (date,shop_id,worker_list_1hr,worker_list_2hr,no_limit_1hr,no_limit_2hr,limit_time)=>{

  try {

    let shop = await query(`select * from Shop where id = ${shop_id} limit 1`);


    let start_time =  moment(date+' '+shop[0].start_time).toDate();
    let end_time = moment(date+' '+shop[0].end_time).toDate();
    if (end_time <= start_time) {
      end_time = moment(end_time).add(22,'hours').toDate();
    }
    let result = [];
    let time_range = [];
    let test1,test2;

    /**
     *  add time segment from start to end
     */
    while(start_time <= end_time ) {
      time_range.push(start_time);
      start_time = moment(start_time).add(30,'minutes').toDate();    //->add(new DateInterval("PT30M")); milisecond
    }
    
    /**
     *  generate promise array
     * @type {Promise<any>[]}
     */
    let excute = time_range.map((ti)=>new Promise((resolve,reject)=>{
      time_option(date, limit_time, 60, ti, shop_id, worker_list_1hr, no_limit_1hr)
        .then(
          (ans1)=>ans1 &&time_option(date, limit_time, 120, ti, shop_id, worker_list_2hr, no_limit_2hr))
        .then((res)=>{
          if(res){
            result.push(moment(ti).format("HH:mm"));
          }
          resolve(res)}
        ).catch(error=>console.warn(error));
    }));
    let status=await Promise.all(excute).then((res)=>{
    }).catch(error=>console.warn(error));
    return result;
  /* origin version
    while(start_time <= end_time ){
      test1 = await time_option(date, limit_time, 60, start_time, shop_id, worker_list_1hr, no_limit_1hr);
      test2 = await time_option(date, limit_time, 120, start_time, shop_id, worker_list_2hr, no_limit_2hr);
      if(test1 && test2){//start_time >= 0 &&
        console.log(moment(start_time).format("HH:mm"),[test1,test2] );
        result.push([moment(start_time).format("HH:mm"), [test1,test2]]);
      }
      start_time = moment(start_time).add(30,'minutes').toDate();    //->add(new DateInterval("PT30M")); milisecond
    }
    return result;
  */
  } catch (Error) {
    console.warn(Error);
  }

};



const time_option = async (date, limit_time, service_time,start, shop_id, worker_list, no_limit)=>{
  let Time = new Date(date);
  let month = Time.getFullYear()+"-"+(Time.getMonth()+1);
  let start_time = start;
  let end_time = start_time;
  end_time = moment(end_time).add(service_time,'m').toDate();
  if(limit_time === true){
    //師傅預約15分鐘
    start_time = moment(start_time).subtract(15,'m').toDate();
    end_time = moment(end_time).add(15,'m').toDate();
  }
  //有空的時間

  let query_1 = "select * from `ServiceProvider` where exists (select * from `Shift` where `Shift`.`service_provider_id` = `ServiceProvider`.`id` and `month` = ?) and not exists (select * from `Leave` where `Leave`.`service_provider_id` = `ServiceProvider`.`id` and `start_time` < ? and `end_time` > ?) and not exists (select * from `Order` inner join `service_provider_order` on `Order`.`id` = `service_provider_order`.`order_id` where `service_provider_order`.`service_provider_id` = `ServiceProvider`.`id` and `status` not in (?, ?, ?) and `start_time` < ? and `end_time` > ?) and `shop_id` = ?"
  let service_providers = await query(query_1,[month,moment(end_time).format("YYYY-MM-DD HH:mm:ss"),moment(start_time).format("YYYY-MM-DD HH:mm:ss"),3,4,6,moment(end_time).format("YYYY-MM-DD HH:mm:ss"),moment(start_time).format("YYYY-MM-DD HH:mm:ss"),shop_id]);

  //let service_providers = await query(" select * from `Shift` where `Shift`.`service_provider_id` in (5, 6, 9, 10, 11, 12, 13, 15, 17, 18, 20, 21, 26, 75, 76, 104, 114, 119, 123, 132, 139) and `month` = '2018-10'");


  if(limit_time === true){
    //扣回 避免出勤錯誤
    start_time = moment(start_time).add(15,'m').toDate();
    end_time = moment(end_time).subtract(15,'m').toDate();
  }

  let service_provider_list = [];

  let service_providers_id = (service_providers.length===0)? null : service_providers.map(v=>v.id);
  let query_11 = "select * from `Shift` where `Shift`.`service_provider_id` in (?) and `month` = ?";
  let shift = await query(query_11,[service_providers_id,month]);

  /*
 *  change service_providers to shift for comparing real duty
 */
  shift.forEach((element)=>{
    let on_duty = new Date(date+" "+element.start_time);
    let off_duty =  new Date(date+" "+element.end_time);
    if(off_duty < on_duty){
      off_duty = moment(off_duty).add(1,"days").toDate(); //->add(new DateInterval("P1D"));
    }
    if(on_duty <= start_time && off_duty >= end_time){
      service_provider_list.push(element.service_provider_id);
    }
  });


  if(limit_time === true){
    //師傅預約15分鐘
    start_time = moment(start_time).subtract(15,'m').toDate();
    end_time = moment(end_time).add(15,'m').toDate();
  }
  /* 不指定人數 */
  let query_2 = "select * from `ServiceProvider` where exists (select * from `Order` inner join `service_provider_order` on `Order`.`id` = `service_provider_order`.`order_id` where `service_provider_order`.`service_provider_id` = `ServiceProvider`.`id` and `status` not in (?, ?, ?) and `start_time` < ? and `end_time` > ?) and `shop_id` = ?";
  service_providers = await query(query_2,[3,4,6,moment(end_time).format("YYYY-MM-DD HH:mm:ss"),moment(start_time).format("YYYY-MM-DD HH:mm:ss"),shop_id]);

  let query_3 = "select *, (select count(*) from `ServiceProvider` inner join `service_provider_order` on `ServiceProvider`.`id` = `service_provider_order`.`service_provider_id` where `service_provider_order`.`order_id` = `Order`.`id`) as `service_providers_count` from `Order` where `start_time` < ? and `end_time` > ? and `status` not in (?, ?, ?) and `shop_id` = ?";
  let order_list = await query(query_3,[moment(end_time).format("YYYY-MM-DD HH:mm:ss"),moment(start_time).format("YYYY-MM-DD HH:mm:ss"),3,4,6,shop_id]);

  if(limit_time === true){
    //扣回 避免出勤錯誤
    start_time = moment(start_time).subtract(15,'m').toDate();
    end_time = moment(end_time).add(15,'m').toDate();
  }

  let no_specific_amount = await no_specific(order_list, service_providers);
  /* 不指定人數 */
  if(!empty(array_diff(worker_list, service_provider_list))){
    return false;
  }
  worker_list = (worker_list===null)? 0: worker_list.length;
  //可用的人 - 不指定的人數 + 訂單指定人數
  return !((service_provider_list.length - no_specific_amount) < (worker_list + no_limit))

};

const no_specific = async (order_list, service_providers)=> {
  let person = 0;
  let flag;
  for (let order in order_list) {
    //@TODO: check the string to int
    let no_limit = order_list[order].person - order_list[order].service_providers_count;
    if (no_limit > 0) {
      for (let service_provider in service_providers) {
        if (service_providers[service_provider].select != true) {
          let query_2 = "select * from `ServiceProvider` where exists (select * from `Order` inner join `service_provider_order` on `Order`.`id` = `service_provider_order`.`order_id` where `service_provider_order`.`service_provider_id` = `ServiceProvider`.`id` and `status` not in (?, ?, ?) and `start_time` < ? and `end_time` > ?)";
          flag = await query(query_2, [3, 4, 6, order_list[order].end_time, order_list[order].start_time]);

          if (flag == null) {
            service_providers[service_provider].select = true;
            no_limit--;
          }
        }
        if (no_limit === 0) break;
      }
    }
    person += no_limit;
  }
  console.log("Person:"+person);
  return person;
};


export {init,query,end,api_service_provider_time}