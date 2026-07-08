<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
        <!-- page content -->
        <div class="right_col" role="main">
          <div class="">
            <div class="clearfix"></div>

            <div class="row">

              <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                  <div class="x_title">
                    <h2 class="text-center">In House O&M SCADA Monitoring</h2>
                    <div class="clearfix"></div>
                  </div>
                  <div class="x_content">
                    <table id="datatable" class="table table-striped table-bordered">
                      <thead>
                        <tr>
                          <th colspan="8">Surandai</th>
                        </tr>
                        <tr>
                          <th>Power</th>
                          <th>Windspeed</th>
                          <th>Generating</th>
                          <th colspan="2">Null Wind</th>
                          <th>Fault</th>
                          <th>Grid Drop</th>
                          <th>No Communication</th>
                        </tr>
                      </thead>
                      <tbody>
                        <tr>
                          <td>6400</td>
                          <td>10.5M/s</td>
                          <td>12</td>
                          <td>1</td>
                          <td></td>
                          <td>2</td>
                          <td>2</td>
                          <td>1</td>
                        </tr>
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>

              <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                  <div class="x_content">
                    <table id="datatable" class="table table-striped table-bordered">
                      <thead>
                        <tr>
                          <th>Loc No</th>
                          <th>Status</th>
                          <th>Capacity</th>
                          <th>Power</th>
                          <th>Wind Speed</th>
                          <th>Roter RPM</th>
                          <th>Generator RPM</th>
                          <th>Pitch Angel</th>
                          <th>Freq</th>
                          <th>Voltage</th>
                          <th>DAILY GEN</th>
                          <th>FEEDER</th>
                        </tr>
                      </thead>
                      <tbody>
                        <tr>
                          <td>Tiger Nixon</td>
                          <td style="background: green;"></td>
                          <td>Edinburgh</td>
                          <td>61</td>
                          <td>2011/04/25</td>
                          <td>$320,800</td>
                          <td>$320,800</td>
                          <td>$320,800</td>
                          <td>$320,800</td>
                          <td>$320,800</td>
                          <td>$320,800</td>
                          <td>$320,800</td>
                        </tr>
                        <tr>
                          <td>Garrett Winters</td>
                          <td style="background: green;"></td>
                          <td>Tokyo</td>
                          <td>63</td>
                          <td>2011/07/25</td>
                          <td>$170,750</td>
                          <td>$170,750</td>
                          <td>$170,750</td>
                          <td>$170,750</td>
                          <td>$170,750</td>
                          <td>$170,750</td>
                          <td>$170,750</td>
                        </tr>
                        <tr>
                          <td>Ashton Cox</td>
                          <td style="background: green;"></td>
                          <td>San Francisco</td>
                          <td>66</td>
                          <td>2009/01/12</td>
                          <td>$86,000</td>
                          <td>$86,000</td>
                          <td>$86,000</td>
                          <td>$86,000</td>
                          <td>$86,000</td>
                          <td>$86,000</td>
                          <td>$86,000</td>
                        </tr>
                        <tr>
                          <td>Cedric Kelly</td>
                          <td style="background: red;"></td>
                          <td>Edinburgh</td>
                          <td>22</td>
                          <td>2012/03/29</td>
                          <td>$433,060</td>
                          <td>$433,060</td>
                          <td>$433,060</td>
                          <td>$433,060</td>
                          <td>$433,060</td>
                          <td>$433,060</td>
                          <td>$433,060</td>
                        </tr>
                        <tr>
                          <td>Airi Satou</td>
                          <td style="background: yellow;"></td>
                          <td>Tokyo</td>
                          <td>33</td>
                          <td>2008/11/28</td>
                          <td>$162,700</td>
                          <td>$162,700</td>
                          <td>$162,700</td>
                          <td>$162,700</td>
                          <td>$162,700</td>
                          <td>$162,700</td>
                          <td>$162,700</td>
                        </tr>
                        <tr>
                          <td>Brielle Williamson</td>
                          <td style="background: green;"></td>
                          <td>New York</td>
                          <td>61</td>
                          <td>2012/12/02</td>
                          <td>$372,000</td>
                          <td>$372,000</td>
                          <td>$372,000</td>
                          <td>$372,000</td>
                          <td>$372,000</td>
                          <td>$372,000</td>
                          <td>$372,000</td>
                        </tr>
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <!-- /page content -->

<?php  $this->load->view('layout/footer'); ?>
