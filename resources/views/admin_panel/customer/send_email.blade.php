@include('main_includes.header_include')

<div id="main-wrapper">
    @include('main_includes.navbar_include')
    @include('main_includes.admin_sidebar_include')

    <div class="content-body rightside-event">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">Send Email To Customer</h4>
                        </div>
                        <div class="card-body">
                            <h5>Customer Details</h5>
                            <table class="table table-bordered">
                                <tr>
                                    <th>Customer Name:</th>
                                    <td>{{ $customer->full_name }}</td>
                                </tr>
                                <tr>
                                    <th>Customer Number:</th>
                                    <td>{{ $customer->customer_number }}</td>
                                </tr>
                                <tr>
                                    <th>Email:</th>
                                    <td>{{ $customer->email }}</td>
                                </tr>
                                <tr>
                                    <th>Phone:</th>
                                    <td>{{ $customer->phone_number }}</td>
                                </tr>
                                <tr>
                                    <th>Address:</th>
                                    <td>{{ $customer->address }}</td>
                                </tr>
                                <tr>
                                    <th>City:</th>
                                    <td>{{ $customer->city }}</td>
                                </tr>
                                <tr>
                                    <th>Gender:</th>
                                    <td>{{ ucfirst($customer->gender) }}</td>
                                </tr>
                                <tr>
                                    <th>Comment:</th>
                                    <td>{{ $customer->comment ?? 'No comment' }}</td>
                                </tr>
                            </table>

                            <hr>

                            <form action="{{ route('customer.send-email.post') }}" method="POST">
                                @csrf
                                <input type="hidden" name="customer_id" value="{{ $customer->id }}">

                                <div class="mb-3">
                                    <label for="email" class="form-label">Email Address</label>
                                    <input type="email" class="form-control" name="email" value="{{ $customer->email }}" required>
                                </div>

                                <div class="mb-3">
                                    <label for="subject" class="form-label">Subject</label>
                                    <input type="text" class="form-control" name="subject" required>
                                </div>

                                <div class="mb-3">
                                    <label for="message" class="form-label">Message</label>
                                    <textarea class="form-control" name="message" rows="5" required></textarea>
                                </div>

                                <button type="submit" class="btn btn-primary">Send Email</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('main_includes.copyright_include')
    @include('main_includes.footer_include')
</div>
