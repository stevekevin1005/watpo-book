// 首頁

const Button = ReactBootstrap.Button,
      Grid = ReactBootstrap.Grid,
      Row = ReactBootstrap.Row,
      Col = ReactBootstrap.Col,
      Well = ReactBootstrap.Well;

class Landpage extends React.Component{
    constructor(props){
        super(props);
    }
    render(){
        const branchData = [{
            name: "民生會館",
            time: "中午 PM 12:00 ~ 凌晨 AM 04:00 ",
            address: "台北市中山區民生東路 2 段 88 號",
            phone: "( 02 ) 2581- 3338"
        },{
            name: "光復會館",
            time: "上午 AM 11:00 ~ 凌晨 AM 03:00",
            address: "台北市松山區光復北路34 號",
            phone: "( 02 ) 2570- 9393"
        }],
        branches = branchData.map((branch, index)=>{
            return (
                
                <Well>
                <p>{branch.name}</p>
                <p>{"營業時間: "+branch.time}</p>
                <p>{"地址: "+branch.address}</p>
                <p>{"預約專線: "+branch.phone}</p>
                </Well>
                
            );
        });
        return(
              <Grid>
                <Row className="show-grid">
                    <div style={{textAlign: "center"}}>
                    <h1>泰和殿</h1>
                    <h2>泰式養生會館</h2>
                        <div style={{width: "300px", margin: "0 auto", maxWidth: "90vw"}}>
                        <Button bsStyle="primary" bsSize="large" block
                            onClick={this.props.toReservation}>
                            預約服務
                        </Button>
                        </div>
                    </div>
                </Row>
                <Row className="show-grid">
                    <Col md={5}>
                        {branches}
                    </Col>
                    <Col md={7}>
                        <Well>
                            <p>服務項目</p>
                        </Well>
                    </Col>
                </Row>
                <Row className="show-grid">
                    <Col md={12}>
                        <h3>地圖</h3>
                    </Col>
                    <Col md={6}>
                        <Well>
                            地圖1
                        </Well>
                    </Col>
                    <Col md={6}>
                        <Well>    
                            地圖2
                        </Well>
                    </Col>
                </Row>
              </Grid>
        );
    }
}

module.exports = Landpage;