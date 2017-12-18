module.exports = (dataKey,payload)=>{
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
            return ({
                type: "SET_TIMELIST",
                payload: payload
            });
    }
}