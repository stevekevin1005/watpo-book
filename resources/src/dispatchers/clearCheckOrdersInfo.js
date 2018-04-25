module.exports = (dataKey)=>{
    switch(dataKey){
        case "name":
            return ({
                type: "CLEAR_CHECKORDERSINFO_NAME",
                payload: null
            });
        case "contactNumber":
            return ({
                type: "CLEAR_CHECKORDERSINFO_CONTACT_NUMBER",
                payload: null
            });
    }
}