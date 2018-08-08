	<thead>
											<tr>
												<th class="vertical-middle">Select</th>
												<th class="vertical-middle">{{trans("config.lbl_vehicle_category") }}</th>
												<th class="vertical-middle">Vehicle Type</th>
												<th class="vertical-middle">Fare Type</th>
											
                                                <th class="vertical-middle">Minimum Kilometre Fare</th>
													<th class="vertical-middle">Ride Fare</th>
                                                <!--<th class="vertical-middle">Kilometre Fare</th>-->
                                                <th class="vertical-middle">Fare/Min of ride</th>
                                                <th class="vertical-middle">Vehicle Waiting Charge/Min</th>
												
                                                <th class="vertical-middle">Time slot </th>
                                                <th class="vertical-middle">Action</th>
											</tr>
										</thead>
										<tbody>
										@foreach ($farelist as $fare_list)
										<tr> 
											@if($fare_list->fare_type != 1)
											<td class="vertical-middle">
													<input type="checkbox" value="{{$fare_list->fare_id}}" />
											</td>
											@else
												<th class="vertical-middle base_fare_rm">
												</th>
											@endif
											<td class="vertical-middle">
											@foreach($ride_category as $rd)
											@if($rd->id == $fare_list->ride_category)
											{{$rd->ride_category}}
											@endif
											@endforeach

											</td>
											<td class="vertical-middle">
												{{$fare_list->car_type}}
												{{ $fare_list->car_board == 1 ? "(W)":""}}
												{{ $fare_list->car_board == 2 ? "(Y)":""}}

											</td>
											<td class="vertical-middle">
												{{ $fare_list->fare_type == 1 ? "Base Fare":""}}
												{{ $fare_list->fare_type == 2 ? "Morning Time":""}}
												{{ $fare_list->fare_type == 3 ? "Night Time":""}}
												{{ $fare_list->fare_type == 4 ? "Peek Time":""}}
												{{ $fare_list->fare_type == 5 ? "Special Time":""}}
												</td>
												
												<td class="vertical-middle">{{ $fare_list->min_fare_amount}}</td>
												<td class="vertical-middle">{{ $fare_list->ride_fare}}</td>
                                                <!--<td class="vertical-middle">{{ $fare_list->below_min_km_fare}}</td>-->
                                                <td class="vertical-middle">{{$fare_list->distance_fare}} / {{$fare_list->distance_time}} Min </td>
												<td class="vertical-middle">{{ $fare_list->waiting_charge}}/ {{$fare_list->waiting_time}} Min </td>
												<td class="vertical-middle">
												{{date("h:i a",strtotime($fare_list->ride_start_time))}} /
												{{date("h:i a",strtotime($fare_list->ride_end_time))}}
												</td>
												<td class="vertical-middle">
												
												
												<a href="{{ url("/view_fare/") }}/{{$fare_list->fare_id}}"><i class="fa fa-eye fa-2x"></i></i></a>
													
													<a href="{{ url("/edit_fare/") }}/{{$fare_list->fare_id}}/edit"><i class="fa fa-edit fa-2x"></i></a>
												@if($fare_list->fare_type != 1)
													@if($fare_list->status == 1)
													<a href="#"  title="Inactivate" onclick="deactivate({{ $fare_list->fare_id}},"ajax_deactive_fare","Fare Details");"><i class="fa fa-close fa-2x "></i></a>
													@else
													<a href="#" title="Activate"  onclick="activate({{ $fare_list->fare_id}},"ajax_active_fare","Fare Details");"><i class="fa fa-check fa-2x"></i></a>
													@endif
												@endif
													</td>
											</tr>
												@endforeach
												</tbody>
									