module.exports = (dataKey,payload)=>{
    switch(dataKey){
        case "shop":
            return ({
                type: "SET_SHOP",
                payload: payload
            });
        case "service":
            return ({
                type: "SET_SERVICE",
                payload: payload
            });
        case "date":
            return ({
                type: "SET_DATE",
                payload: payload
            });
        case "time":
            return ({
                type: "SET_TIME",
                payload: payload
            });
        case "operator":
            return ({
                type: "SET_OPERATOR",
                payload: payload
            });
        case "room":
            return ({
                type: "SET_ROOM",
                payload: payload
            });
        case "guestNum":
            return ({
                type: "SET_GUESTNUM",
                payload: payload
            });
        case "name":
            return ({
                type: "SET_NAME",
                payload: payload
            });
        case "contactNumber":
            return ({
                type: "SET_CONTACT_NUMBER",
                payload: payload
            });
    }
}