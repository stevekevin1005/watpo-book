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
    sourceData:
    (state={}, action)=>{
      let result = state?JSON.parse(JSON.stringify(state)):{};
      switch(action.type){
        case "SET_SHOPS":
          result.shops = action.payload;
          return result;
        case "SET_SERVICES":
          result.services = action.payload;
          return result;
        case "SET_TIMELIST":
          result.timeList = action.payload;
          return result;
        case "SET_SELECTED_DETAIL":
          result.selectedDetail = action.payload;
          return result;
        case "CLEAR_TIMELIST":
          result.timeList = undefined;
          return result;
        case "CLEAR_SELECTED_DETAIL":
          result.selectedDetail = undefined;
          return result;
        default:
          return state;
      }
    },
    reservation:
    (state={operator:[]}, action)=>{
      let result = JSON.parse(JSON.stringify(state));
      switch(action.type){
        // generally are ids of data
        case "SET_SHOP":
          result.shop = action.payload;
          return result;
        case "SET_SERVICE":
          result.service = action.payload;
          return result;
        case "SET_DATE":
          result.date = action.payload;
          return result;
        case "SET_TIME":
          result.time = action.payload;
          return result;
        case "SET_OPERATOR":
          result.operator[action.payload.index] = action.payload.data;
          return result;
        case "SET_ROOM":
          result.room = action.payload;
          return result;
        case "SET_GUESTNUM":
          result.guestNum = action.payload;
          return result;
        case "SET_NAME":
          result.name = action.payload;
          return result;
        case "SET_CONTACT_NUMBER":
          result.contactNumber = action.payload;
          return result;
        case "CLEAR_STEP1":
          result.date = undefined;
          result.time = undefined;
          return result;
        case "CLEAR_STEP2":
          result.operator = [];
          result.room = undefined;
          result.guestNum = 1;
          result.name = undefined;
          result.contactNumber = undefined;
          return result;
        case "CLEAR":
          return {operator:[]};
        case "CLEAR_OPERATOR":
          result.operator = result.operator.slice(0, action.payload);
          return result;
        default:
          return state;
      }
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