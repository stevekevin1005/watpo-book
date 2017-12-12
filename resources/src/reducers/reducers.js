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
    // Object: _id, name, rooms, valid
    currentUser:
      (state = null, action)=>{
        var result;
        switch(action.type){
          case "LOGIN":
            if(action.payload==null) break;
            return action.payload;
          case "LOGOUT":
            return null;
          case "LEAVEROOM":
            var index = state.rooms.findIndex((el)=>{
              return el._id == action.payload._id;
            });
            if(index>=0){
              result = JSON.parse(JSON.stringify(state));
              result.rooms.splice(index,1);
              return result;
            }
          case "ADDTOROOM":
            // pass in id and name of room
            result = JSON.parse(JSON.stringify(state));
            result.rooms.push({_id:action.payload._id, name: action.payload.name});
            return result;
        }
        return state;
      },
      // Object: _id, name, members, log, valid
      currentRoom:
      (state = null, action)=>{
        if(action.type=="CHANGEROOM" && action.payload!==undefined) return action.payload;
        else if(action.type == "ADDLOG") {
          let result = JSON.parse(JSON.stringify(state));
          result.log.push(action.payload);
          return result;
        }
        return state;
      }
  };

  module.exports = reducers;