// 負責渲染和清理資料

import Nav from "./Nav";
import Landpage from "./Landpage";
import Reservation from "./Reservation";
import CheckOrders from "./CheckOrders";
import { Switch, Route, BrowserRouter } from "react-router-dom";
import i18n from '../i18n';

class App extends React.Component{
    constructor(props){
        super(props);       
    }
    render(){
        return(
                <BrowserRouter>
                    <div>
                        <Nav/>
                        <Switch>
                            <Route exact path="/" component={Landpage} />
                            <Route path="/reservation/:step" component={Reservation} />
                            <Route path="/checkOrders/:step" component={CheckOrders} />
                        </Switch>
                    </div>
                </BrowserRouter>
        );
    }
}

module.exports = App;