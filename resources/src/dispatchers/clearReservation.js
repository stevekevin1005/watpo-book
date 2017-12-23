module.exports = (dataKey,fn,index)=>{
    // 0-based
    switch(dataKey){
        // date and time
        case "step1":
        setTimeout(fn,500);
            return ({
                type: "CLEAR_STEP1",
                payload: null
            });
        // operator, room, guest number, name, contact number
        case "step2":
            return ({
                type: "CLEAR_STEP2",
                payload: null
            });
        case "all":
            return ({
                type: "CLEAR",
                payload: null
            });
        case "operator":
            return ({
                type: "CLEAR_OPERATOR",
                payload: index
            });
    }
}