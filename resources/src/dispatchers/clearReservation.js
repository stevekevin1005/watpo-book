module.exports = (dataKey)=>{
    // 0-based
    switch(dataKey){
        // date and time
        case "step1":
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
    }
}