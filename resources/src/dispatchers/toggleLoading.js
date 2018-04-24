module.exports = (loadingOrNot)=>{
    return ({
        type: "TOGGLE_LOADING",
        payload: loadingOrNot
    });
}