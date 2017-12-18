module.exports = (dataKey,payload)=>{
    switch(dataKey){
        case "timelist":
            return ({
                type: "CLEAR_TIMELIST",
                payload: null
            });
    }
}