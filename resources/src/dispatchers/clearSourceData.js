module.exports = (dataKey,payload)=>{
    switch(dataKey){
        case "timeList":
            return ({
                type: "CLEAR_TIMELIST",
                payload: null
            });
        case "selectedDetail":
            return ({
                type: "CLEAR_SELECTED_DETAIL",
                payload: null
            });
    }
}