import redux from "redux";

const reducers = {
    lastAction:
    (state = null, action)=>{
      return action.type;
    },
    loading:
    (state = false, action)=>{
      if(action.type=="TOGGLELOADING"){
        return action.payload;
      }else return state;
    },
    lang:
    (state=null, action)=>{
      if(action.type == "CHANGELANG"){
        console.log(action.payload);
        return action.payload;
      }else return state;
    }
  };

  module.exports = reducers;