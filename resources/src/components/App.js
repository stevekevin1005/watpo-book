// 負責渲染和清理資料

import Nav from "./Nav";
import Landpage from "./Landpage";
import Reservation from "./Reservation";



class App extends React.Component{
    constructor(props){
        super(props);
        this.state = {show: 0};

        this.changeView = this.changeView.bind(this);
    }
    changeView(index){
        if(index === undefined || this.state.show === index) return;
        switch (index) {
            case 0:
                // if(this.prop.reservation != null) this.props.clearReservation();
                break;
        }
        this.setState({show: index});
    }
    render(){
        let el;
        switch(this.state.show){
            case 0:
                el = <Landpage toReservation={()=>{this.changeView(1);}}/>;
                break;
            case 1:
                el = <Reservation toReservation={()=>{this.changeView(1);}}/>;
                break;
        }

        return(
            <div>
                <Nav 
                    toIndex={()=>{this.changeView(0);}}
                    toReservation={()=>{this.changeView(1);}}
                />
                {el}
            </div>
        );
    }
}

module.exports = App;