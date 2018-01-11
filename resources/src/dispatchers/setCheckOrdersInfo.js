module.exports = (dataKey,payload)=>{
    switch(dataKey){
        case "name":
            return ({
                type: "SET_CHECKORDERSINFO_NAME",
                payload: payload
            });
        case "contactNumber":
            return ({
                type: "SET_CHECKORDERSINFO_CONTACT_NUMBER",
                payload: payload
            });
    }
}