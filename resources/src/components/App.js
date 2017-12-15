// 負責渲染和清理資料

import Nav from "./Nav";
import Landpage from "./Landpage";
import Reservation from "./Reservation";
import { Switch, Route, BrowserRouter } from "react-router-dom";


class App extends React.Component{
    constructor(props){
        super(props);
    }
    componentDidReceiveProps(){
        
    }
    render(){
        return(
                <BrowserRouter>
                    <div>
                        <Nav/>
                        <Switch>
                            <Route exact path="/" component={Landpage} />
                            <Route path="/reservation/:step" component={Reservation} />
                        </Switch>
                    </div>
                </BrowserRouter>
        );
    }
}

module.exports = App;