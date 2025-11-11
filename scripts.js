let currentRole = 'student';
        let currentUser = null;

        function switchRole(role) {
            currentRole = role;
            const buttons = document.querySelectorAll('.role-btn');
            buttons.forEach(btn => btn.classList.remove('active'));
            event.target.classList.add('active');

            // Update placeholder text based on role
            const usernameInput = document.getElementById('username');
            if (role === 'admin') {
                usernameInput.placeholder = 'Enter admin username';
            } else {
                usernameInput.placeholder = 'Enter student number';
            }
        }

        function togglePassword() {
            const passwordInput = document.getElementById('password');
            const toggleBtn = event.target;
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                toggleBtn.textContent = '🙈';
            } else {
                passwordInput.type = 'password';
                toggleBtn.textContent = '👁️';
            }
        }

        function handleLogin(e) {
            e.preventDefault();
            const username = document.getElementById('username').value.trim();
            const password = document.getElementById('password').value;

            const formData = new FormData();
            formData.append('username', username);
            formData.append('password', password);
            formData.append('role', currentRole);

            fetch('login.php', { method: 'POST', body: formData })
                .then(res => res.json())
                .then(data => {
                    if (!data.success) {
                        alert(data.message || 'Login failed');
                        return;
                    }
                    const u = data.user;
                    currentUser = {
                        id: u.id,
                        name: `${u.firstname} ${u.lastname}`.trim(),
                        role: u.role === 'admin' || u.role === 'Admin' ? 'Admin' : 'Student',
                        username: u.username,
                        student_no: u.student_no,
                        employee_no: u.employee_no,
                        course: u.grade_level,
                        section: u.section,
                        contact: u.contact,
                        photo: u.profile_photo || 'uploads/profile/default_profile.png'
                    };
                    showDashboard();
                })
                .catch(() => {
                    alert('Server error. Please try again later.');
                });
        }

        function showDashboard() {
            //profile photo logic if student or admin
            if (currentUser.role === 'Student') {
                currentUser.photo = 'uploads/profile/default_profile.png';
            } else {
                currentUser.photo = 'uploads/profile/default_profile.png';
            }
            
            document.getElementById('sidebarProfilePhoto').src = currentUser.photo;
            document.getElementById('loginPage').classList.add('hidden');
            document.getElementById('dashboardPage').classList.add('active');

            // Update user info in sidebar
            document.getElementById('displayUserName').textContent = currentUser.name;
            document.getElementById('displayUserRole').textContent = currentUser.role;
            document.getElementById('welcomeMessage').textContent = `Welcome back, ${currentUser.name}!`;

            // Show/hide admin-only sections
            if (currentUser.role === 'Admin') {
                document.getElementById('navStudents').style.display = 'flex';
                document.getElementById('navOrganizers').style.display = 'flex';
                document.getElementById('navEvents').style.display = 'flex';
                // Load initial data for admin views
                loadOrganizationsIntoSelects();
                loadOrganizationsTable();
                loadEventsTable();
            } else {
                document.getElementById('navStudents').style.display = 'none';
                document.getElementById('navOrganizers').style.display = 'none';
                const navEvents = document.getElementById('navEvents');
                if (navEvents) navEvents.style.display = 'none';
            }
            // Load dashboard stats
            refreshDashboard();
        }

      function logout() {
  const alertBox = document.getElementById('logoutAlert');
  alertBox.classList.remove('hidden');

  // Buttons
  const confirmBtn = document.getElementById('confirmLogout');
  const cancelBtn = document.getElementById('cancelLogout');

  // Remove old listeners to prevent stacking
  confirmBtn.replaceWith(confirmBtn.cloneNode(true));
  cancelBtn.replaceWith(cancelBtn.cloneNode(true));

  // Re-select new clones
  const newConfirmBtn = document.getElementById('confirmLogout');
  const newCancelBtn = document.getElementById('cancelLogout');

  // Confirm logout
    newConfirmBtn.addEventListener('click', () => {
        fetch('logout.php', { method: 'POST' })
            .finally(() => {
                currentUser = null;
                document.getElementById('loginPage').classList.remove('hidden');
                document.getElementById('dashboardPage').classList.remove('active');
                document.getElementById('loginForm').reset();
                const pages = document.querySelectorAll('.page-content');
                pages.forEach(page => page.classList.remove('active'));
                document.getElementById('dashboard').classList.add('active');
                const navItems = document.querySelectorAll('.nav-item');
                navItems.forEach(item => item.classList.remove('active'));
                navItems[0].classList.add('active');
                alertBox.classList.add('hidden');
            });
    });

  // Cancel logout
  newCancelBtn.addEventListener('click', () => {
    alertBox.classList.add('hidden');
  });
}

        function toggleSidebar() {
            const sidebar = document.querySelector('.sidebar');
            const mainContent = document.querySelector('.main-content');
            const toggleBtn = document.querySelector('.toggle-btn');
            
            sidebar.classList.toggle('collapsed');
            mainContent.classList.toggle('expanded');
            toggleBtn.classList.toggle('collapsed');
        }

        function showPage(pageName) {
            // Hide all pages
            const pages = document.querySelectorAll('.page-content');
            pages.forEach(page => page.classList.remove('active'));
            
            // Show selected page
            document.getElementById(pageName).classList.add('active');
            
            // Update active nav item
            const navItems = document.querySelectorAll('.nav-item');
            navItems.forEach(item => item.classList.remove('active'));
            event.target.closest('.nav-item').classList.add('active');
        }

        // Add smooth scroll behavior
        document.querySelectorAll('.nav-item').forEach(item => {
            item.addEventListener('click', function() {
                window.scrollTo({ top: 0, behavior: 'smooth' });
            });
        });

        // Bootstrap: check active session and set up listeners
        document.addEventListener('DOMContentLoaded', function() {
            // session check
            fetch('me.php')
              .then(r => r.json())
              .then(d => {
                if (d.authenticated && d.user) {
                    const u = d.user;
                    currentUser = {
                        id: u.id,
                        name: `${u.firstname} ${u.lastname}`.trim(),
                        role: u.role === 'admin' || u.role === 'Admin' ? 'Admin' : 'Student',
                        username: u.username,
                        student_no: u.student_no,
                        employee_no: u.employee_no,
                        course: u.grade_level,
                        section: u.section,
                        contact: u.contact,
                        photo: u.profile_photo || 'uploads/profile/default_profile.png'
                    };
                    showDashboard();
                }
              })
              .catch(() => {});

            // Prevent form submission on enter in remember me checkbox
            const loginForm = document.getElementById('loginForm');
            if (loginForm) {
                loginForm.addEventListener('keypress', function(e) {
                    if (e.key === 'Enter' && e.target.type !== 'submit') {
                        e.preventDefault();
                        loginForm.dispatchEvent(new Event('submit'));
                    }
                });
            }
        });

                // ===== New dynamic functions =====
                function refreshDashboard() {
                        // Populate basic stats from APIs
                                    fetch('api/organizations.php?action=list')
                            .then(r=>r.json()).then(d=>{
                                if(d.success){
                                    document.getElementById('statOrganizers').textContent = d.data.length;
                                    const orgSelect = document.getElementById('studentsOrgFilter');
                                    if (orgSelect) {
                                        orgSelect.innerHTML = d.data.map(o=>`<option value="${o.id}">${o.name}</option>`).join('');
                                        if (orgSelect.value) loadStudentsTable(orgSelect.value);
                                        orgSelect.onchange = (e)=>loadStudentsTable(e.target.value);
                                    }
                                                const dashOrgsTbody = document.querySelector('#dashboardOrgsTable tbody');
                                                if (dashOrgsTbody) {
                                                    dashOrgsTbody.innerHTML = d.data.map(o=>`<tr><td>${o.name}</td><td>${o.description||''}</td><td>${o.members}</td></tr>`).join('');
                                                }
                                }
                            }).catch(()=>{});
                        fetch('api/events.php?action=list').then(r=>r.json()).then(d=>{
                            if(d.success){
                                document.getElementById('statActiveEvents').textContent = d.data.length;
                                const tbody = document.querySelector('#upcomingEventsTable tbody');
                                if (tbody) {
                                    tbody.innerHTML = d.data.slice(0,5).map(ev=>`<tr><td>${ev.name}</td><td>${ev.event_date}</td><td>${ev.location||''}</td><td>-</td><td><button class="btn btn-primary" onclick="showPage('attendance')">Open</button></td></tr>`).join('');
                                }
                            }
                        }).catch(()=>{});
                    }

                function loadOrganizationsIntoSelects(){
                    fetch('api/organizations.php?action=list').then(r=>r.json()).then(d=>{
                        if(!d.success) return;
                        const opts = d.data.map(o=>`<option value="${o.id}">${o.name}</option>`).join('');
                        const selects = ['orgSelectForMember','eventOrgSelect','attOrgSelect','studentsOrgFilter'];
                        selects.forEach(id=>{ const el = document.getElementById(id); if(el) el.innerHTML = opts; });
                        // Also load events and attendance dependent selects
                        const attOrg = document.getElementById('attOrgSelect');
                        if (attOrg && attOrg.value) { loadEventsForOrg(attOrg.value, 'attEventSelect'); }
                    }).catch(()=>{});
                }

                function loadOrganizationsTable(){
                    fetch('api/organizations.php?action=list').then(r=>r.json()).then(d=>{
                        if(!d.success) return;
                        const tbody = document.querySelector('#orgsTable tbody');
                        if (!tbody) return;
                        tbody.innerHTML = d.data.map(o=>`<tr><td>${o.name}</td><td>${o.description||''}</td><td>${o.members}</td></tr>`).join('');
                    });
                }

                function createOrganization(){
                    const name = document.getElementById('orgName').value.trim();
                    const description = document.getElementById('orgDescription').value.trim();
                    if(!name){ alert('Organization/Class name required'); return; }
                    const fd = new FormData(); fd.append('action','create'); fd.append('name',name); fd.append('description',description);
                    fetch('api/organizations.php', { method:'POST', body: fd })
                        .then(r=>r.json()).then(d=>{ if(d.success){ alert('Created'); loadOrganizationsIntoSelects(); loadOrganizationsTable(); } else { alert(d.message||'Failed'); } });
                }

                function addMemberToOrganization(){
                    const orgId = document.getElementById('orgSelectForMember').value;
                    const studentNo = document.getElementById('memberStudentNo').value.trim();
                    if(!orgId || !studentNo){ alert('Select organization and student number'); return; }
                    const fd = new FormData(); fd.append('action','add_member'); fd.append('org_id',orgId); fd.append('student_no',studentNo);
                    fetch('api/organizations.php', { method:'POST', body: fd })
                        .then(r=>r.json()).then(d=>{ if(d.success){ alert('Added'); loadOrganizationsTable(); const cur = document.getElementById('studentsOrgFilter'); if (cur && cur.value==orgId) loadStudentsTable(orgId); } else { alert(d.message||'Failed'); } });
                }

                function loadEventsTable(){
                    fetch('api/events.php?action=list').then(r=>r.json()).then(d=>{
                        if(!d.success) return; const tbody = document.querySelector('#eventsTable tbody'); if(!tbody) return;
                        tbody.innerHTML = d.data.map(ev=>`<tr><td>${ev.name}</td><td>${ev.org_name}</td><td>${ev.event_date}</td><td>${ev.location||''}</td></tr>`).join('');
                    });
                }

                function loadEventsForOrg(orgId, selectId){
                    fetch('api/events.php?action=list&org_id='+encodeURIComponent(orgId)).then(r=>r.json()).then(d=>{
                        const sel = document.getElementById(selectId); if(!sel) return;
                        if(!d.success){ sel.innerHTML=''; return; }
                        sel.innerHTML = d.data.map(ev=>`<option value="${ev.id}">${ev.name} (${ev.event_date})</option>`).join('');
                    });
                }

                function createEvent(){
                    const orgId = document.getElementById('eventOrgSelect').value;
                    const name = document.getElementById('eventName').value.trim();
                    const date = document.getElementById('eventDate').value;
                    const loc = document.getElementById('eventLocation').value.trim();
                    if(!orgId || !name || !date){ alert('Select org and fill name/date'); return; }
                    const fd = new FormData(); fd.append('action','create'); fd.append('org_id',orgId); fd.append('name',name); fd.append('date',date); fd.append('location',loc);
                    fetch('api/events.php', { method:'POST', body: fd })
                        .then(r=>r.json()).then(d=>{ if(d.success){ alert('Event created'); loadEventsTable(); const attOrg = document.getElementById('attOrgSelect'); if(attOrg && attOrg.value==orgId) loadEventsForOrg(orgId,'attEventSelect'); } else { alert(d.message||'Failed'); } });
                }

                function loadStudentsTable(orgId){
                    fetch('api/students.php?org_id='+encodeURIComponent(orgId)).then(r=>r.json()).then(d=>{
                        if(!d.success) return; const tbody = document.querySelector('#studentsTable tbody'); if(!tbody) return;
                        document.getElementById('statTotalStudents').textContent = d.data.length;
                        tbody.innerHTML = d.data.map(s=>`<tr><td>${s.student_no||''}</td><td>${s.name}</td><td>${s.course||''}</td><td>${s.section||''}</td><td>${s.contact||''}</td></tr>`).join('');
                    });
                }

                function loadAttendanceRoster(){
                    const orgId = document.getElementById('attOrgSelect').value;
                    if(!orgId){ alert('Select organization'); return; }
                    loadEventsForOrg(orgId,'attEventSelect');
                    fetch('api/attendance.php?action=roster&org_id='+encodeURIComponent(orgId)).then(r=>r.json()).then(d=>{
                        if(!d.success) return; const tbody = document.querySelector('#attendanceTable tbody'); if(!tbody) return;
                                const isAdmin = currentUser && currentUser.role === 'Admin';
                                tbody.innerHTML = d.data.map(s=>`<tr data-user-id="${s.id}"><td>${s.student_no||''}</td><td>${s.firstname} ${s.lastname}</td><td><input type="checkbox" class="att-present" ${!isAdmin?'disabled':''}></td><td><input type="checkbox" class="att-late" ${!isAdmin?'disabled':''}></td></tr>`).join('');
                                const saveBtn = document.querySelector('#attendance .content-section button.btn.btn-primary:nth-of-type(2)');
                                if (saveBtn) saveBtn.style.display = isAdmin ? 'inline-block' : 'none';
                    });
                }

                function saveAttendance(){
                    const eventId = document.getElementById('attEventSelect').value;
                    if(!eventId){ alert('Select event'); return; }
                    const rows = Array.from(document.querySelectorAll('#attendanceTable tbody tr'));
                    const entries = rows.map(tr=>{
                        const uid = parseInt(tr.getAttribute('data-user-id'));
                        const present = tr.querySelector('.att-present').checked;
                        const late = tr.querySelector('.att-late').checked;
                        let status = 'absent';
                        if (present) status = 'present';
                        else if (late) status = 'late';
                        return { user_id: uid, status };
                    });
                    const fd = new FormData(); fd.append('action','save'); fd.append('event_id', eventId); fd.append('entries', JSON.stringify(entries));
                    fetch('api/attendance.php', { method:'POST', body: fd })
                        .then(r=>r.json()).then(d=>{ if(d.success){ alert('Attendance saved'); } else { alert(d.message||'Failed'); } });
                }

                // Expose some functions to window for onclick attributes
                window.createOrganization = createOrganization;
                window.addMemberToOrganization = addMemberToOrganization;
                window.createEvent = createEvent;
                window.loadAttendanceRoster = loadAttendanceRoster;
                window.saveAttendance = saveAttendance;
                window.loadEventsForOrg = loadEventsForOrg;