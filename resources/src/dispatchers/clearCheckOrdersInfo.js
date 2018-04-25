module.exports = (dataKey, value = null) => {
    switch (dataKey) {
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
        case "UserVerifiy":
            return ({ type: "VERIFIED_USER", isverified: value })
    }
}