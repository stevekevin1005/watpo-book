module.exports = (dataKey,payload,fn,errorHandle)=>{
    switch(dataKey){
        case "shops":
            return ({
                type: "SET_SHOPS",
                payload: payload
            });
        case "services":
            return ({
                type: "SET_SERVICES",
                payload: payload
            });
        case "timelist":
            return function(dispatch){
                axios({
                    method: "get",
                    url: "../api/time_list",
                    params: {
                        shop_id: payload.shop,
                        service_id: payload.service,
                        date: payload.date
                    },
                    headers: {'X-CSRF-TOKEN': payload.token},
                    responseType: 'json'
                })
                .then(function (response) {
                    if(response.statusText == "OK"){
                        dispatch ({
                            type: "SET_TIMELIST",
                            payload: response.data
                        });
                        fn(response.data.length);
                    }
                })
                .catch(function (error) {
                    console.log(error);
                    errorHandle();
                });
            }
        case "selectedDetail":
            return ({
                type: "SET_SELECTED_DETAIL",
                payload: payload
            });
    }
}