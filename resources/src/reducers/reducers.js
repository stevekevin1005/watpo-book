import redux from "redux";

const reducers = {
    lastAction:
    (state = null, action)=>{
      return action.type;
    },
    loading:
    (state = false, action)=>{
      if(action.type=="TOGGLE_LOADING"){
        return action.payload;
      }else return state;
    },
    checkOrdersInfo:
    (state={},action)=>{
      let result = JSON.parse(JSON.stringify(state));
      switch(action.type){
        case "SET_CHECKORDERSINFO_NAME":
          result.name = action.payload;
          return result;
        case "SET_CHECKORDERSINFO_CONTACT_NUMBER":
          result.contactNumber = action.payload;
          return result;
        case "CLEAR_CHECKORDERSINFO_NAME":
          result.name = undefined;
          return result;
        case "CLEAR_CHECKORDERSINFO_CONTACT_NUMBER":
          result.contactNumber = undefined;
          return result;
        default:
          return state;
      }
    }
  };

  module.exports = reducers;