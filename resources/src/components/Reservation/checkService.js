// 負責寫資料(分店,服務)到global state

const Button = ReactBootstrap.Button,
        Grid = ReactBootstrap.Grid,
        Row = ReactBootstrap.Row,
        Col = ReactBootstrap.Col,
        FormGroup = ReactBootstrap.FormGroup,
        FormControl = ReactBootstrap.FormControl,
        ControlLabel = ReactBootstrap.ControlLabel;

class CheckService extends React.Component{
    constructor(props){
        super(props);
    }
    render(){
        return(
            <Grid>
                <Row className="show-grid">
                    <Col md={7}>
                        <FormGroup controlId="formControlsSelect">
                            <ControlLabel>分店</ControlLabel>
                            <FormControl componentClass="select" placeholder="select">
                                <option value="select">select</option>
                                <option value="other">...</option>
                            </FormControl>
                        </FormGroup>
                    </Col>
                    <Col md={7}>
                        <FormGroup controlId="formControlsSelect">
                            <ControlLabel>服務</ControlLabel>
                            <FormControl componentClass="select" placeholder="select">
                                <option value="select">select</option>
                                <option value="other">...</option>
                            </FormControl>
                        </FormGroup>
                    </Col>
                </Row>
            </Grid>
        );
    }
}

module.exports = CheckService;