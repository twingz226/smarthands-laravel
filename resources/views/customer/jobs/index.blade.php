                                <td>{{ $job->status }}</td>
                                <td>
                                    @if($job->status === 'completed' && !$job->rating()->exists())
                                        <a href="{{ route('customer.jobs.rate', $job->id) }}" 
                                           class="btn btn-warning btn-sm">
                                            <i class="fas fa-star"></i> Rate Service
                                        </a>
                                    @elseif($job->rating()->exists())
                                        <span class="badge bg-success">
                                            <i class="fas fa-check"></i> Rated
                                        </span>
                                    @endif
                                </td> 