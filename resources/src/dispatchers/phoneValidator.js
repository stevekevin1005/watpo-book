module.exports = (dataKey, data) => {
    switch (dataKey) {
        case "HANDLE_MODAL":
            return ({
                type: "HANDLE_MODAL",
                payload: data
            });
        case "SMS_IS_SENT":
            return ({
                type: "SMS_IS_SENT",
                payload: data
            });
        case "RE_ENTER_CODE":
            return ({
                type: "RE_ENTER_CODE",
                payload: data
            });
        case "SMS_ERROR":
            return ({
                type: "SMS_ERROR",
                payload: data
            });

    }
}