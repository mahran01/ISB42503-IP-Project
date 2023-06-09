				<!-- End of page-specific content. -->
				</main>
				<!-- Script 3.3 - footer.php -->
			</div><!-- End of "content" DIV. -->
			
			<div id="footer">
				<p>ISB 42503 :: INTERNET PROGRAMMING - PROJECT</p>
			</div>
		
		</div><!-- End of "wrapper" DIV. -->
		<script>
			const changeURL = (newUrl) => {
			// Change the URL without reloading the page
				history.pushState(null, '', newURL);
			}
			const redirectPost = (url, retainPost = false) => {
				const data = {url: url};
				if (retainPost)
				{	
					const post = <?php echo json_encode($_POST)?>;
					$.each(post, function(key, value) {
						data[key] = value;
					});
				}
				$.ajax({
					method: 'POST',
					url: '../src/requires/router/handleRequest.php',
					data : data,
					success: function(response) {
						// // Update the content dynamically
						$('#main').html(response);
					},
					error: function() {
						// Handle errors
						console.log('Error occurred');
					}
				});
			}
			$(document).ready(function() {
				// Handle router links click event
				$('.router-link').click(function(e) {
					e.preventDefault(); // Prevent default link behavior
		
					// Get the URL from the clicked link
					var url = $(this).attr('href');
					redirectPost(url);
				});
			});
		</script>
		<script src="../src/js/app.js"></script>
	</body>
</html>