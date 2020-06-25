<?php
            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                // handle the form
                require('../db_connect.php');

                # Trim all the incoming data
                $trimmed = array_map('trim', $_POST);

                #Assume invalid data inputs
                $n = $e = FALSE;

                # Check for the names
                if (preg_match('/^[A-Z \'.-]{2,20}$/i', $trimmed['first_name'])) {
                    $fn = mysqli_real_escape_string($dbc, $trimmed['first_name']);
                } else {
                    echo '<p class ="error">Please enter your first name!</p>';
                }

                if (preg_match('/^[A-Z \'.-]{2,20}$/i', $trimmed['last_name'])) {
                    $ln = mysqli_real_escape_string($dbc, $trimmed['last_name']);
                } else {
                    echo '<p class ="error">Please enter your last name!</p>';
                }

                # Check for an email address
                if (preg_match('/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$/', $trimmed['email'])) {
                    $e =  mysqli_real_escape_string($dbc, $trimmed['email']);
                } else {
                    echo '<p class="error">Please enter a valid email address.</p>';
                }

                // if (filter_var($trimmed['email'], FILTER_VALIDATE_EMAIL)) {
                //     $e = mysqli_real_escape_string($dbc, $trimmed['email']);
                // } else {
                //     echo '<p class ="error">Please enter a valid email address!</p>';
                // }

                # If everything ok
                if ($fn && $ln && $e) {
                    # Make sure that email address is available
                    $q = "SELECT subscriber_id FROM subscribers WHERE email = '$e'";
                    $r = mysqli_query($dbc, $q) OR trigger_error("Query: $q\n<br>MySQL Error: ".
                        mysqli_error($dbc));
                    if (mysqli_num_rows($r) == 0) { // If email is not taken
                        # Add the user to the databaase
                        $q = "INSERT INTO subscribers (first_name, last_name, email, subscription_date) VALUES ('$fn', '$ln', '$e', NOW() )";
                        $r = mysqli_query($dbc, $q) OR trigger_error("Query: $q\n<br>MySQL Error: ". mysqli_error($dbc));

                        if (mysqli_affected_rows($dbc) == 1)  {
                            // if it ran ok
                            echo '<h4 class="alert alert-success role="alert" text-center">Thank you for subscribing to our newsletter. We will keep you updated.
                                <button type="button" class="close" data-dismiss="alert" arial-label="close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </h4>';
                        }  

                        else {
                            // If it did not ran OK
                            echo '<h4 class="text-warning">You could not be subscribed due to system error. We apologize for any inconsistency</h4>';
                        }
                    } else {
                        // The email address is already taken 
                        echo '<h4 class="text-danger">The email used has already been registered. Please use a different email.</h4>';
                    }

                } else {
                    // If one of the data tests failed.
                    echo '<h4 class="text-danger">Please try again!</h4>';
                }

                # Close the database connection.
                mysqli_close($dbc);
            }
    ?>

<div class="newsletter">
                <div class="container">
                    <div class="newsletter-heading text-center">
                        <h4>Stay connected with us. Join the newsletter to receive fresh info.</h4>
                    </div>
                    <form action="index.php" method="post">
                        <div class="form-input">
                            <input type="text" class="text-input" name="first_name" placeholder="first name"/>
                        </div>
                        <div class="form-input">
                            <input type="text" class="text-input" name="last_name" placeholder="last name"/>
                        </div>
                        <div class="form-input">
                            <input type="email" name="email" class="text-input" placeholder="email"/>
                        </div>
                        <input type="submit" name="submit" class=" join-btn " value="Join Now"></input>
                    </form>
                </div>    
            </div>