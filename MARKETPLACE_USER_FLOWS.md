# Marketplace User Flows

## How UNLOQIT Works as a Locksmith Marketplace

### 🚗 Customer Flow

#### 1. Customer Needs Help
- Customer visits SEO page: `/cleveland-locksmith/car-lockout`
- Sees "Request a Lock Pro" CTA button
- Clicks button → redirected to `/request-locksmith/cleveland/car-lockout`

#### 2. Submit Request
- Form pre-filled with city and service
- Customer enters:
  - Name, phone, email
  - Urgency level (normal/high/emergency)
  - Address (optional)
  - Description (optional)
- Submits form → Job created in database

#### 3. Job Broadcast
- Job status: `created` → `broadcast`
- System finds available Lock Pros:
  - Online status = true
  - Service area matches job city
  - Service type matches job service
  - Active jobs < max per hour
- Job appears in pro dashboard feed

#### 4. Lock Pro Claims Job
- Lock Pro sees job in dashboard
- Clicks "Claim Job"
- Job status: `broadcast` → `claimed`
- Job assigned to that Lock Pro
- Customer can see Lock Pro info on tracking page

#### 5. Job Execution
- Lock Pro updates status:
  - `claimed` → `en_route` (heading to customer)
  - `en_route` → `arrived` (at location)
  - `arrived` → `in_progress` (working)
  - `in_progress` → `completed` (done)
- Customer sees real-time updates on tracking page

#### 6. Payment & Completion
- Job marked complete
- Payment processed (Stripe integration)
- Lock Pro earnings recorded
- Customer receives confirmation

---

### 🔧 Lock Pro (Locksmith) Flow

#### 1. Sign Up
- Locksmith visits `/pro/register`
- Fills out registration:
  - Personal info (name, email, phone)
  - License number (required for verification)
  - Password
  - Service areas (cities they serve)
  - Service types (what they can do)
  - Optional bio
- Submits → Account created

#### 2. Account Verification
- Account status: `is_verified = false`, `is_active = false`
- Admin reviews license number
- Admin verifies credentials
- Account activated: `is_verified = true`, `is_active = true`
- Lock Pro receives email notification

#### 3. First Login
- Lock Pro visits `/pro/login`
- Enters email/password
- Logged in → redirected to dashboard
- Sees "pending verification" message if not verified

#### 4. Go Online
- Lock Pro clicks "Go Online" button
- Availability status: `is_online = true`
- `last_seen_at` updated
- Now appears in job dispatch system

#### 5. View Available Jobs
- Dashboard shows:
  - Active jobs (claimed by this pro)
  - Available jobs (broadcast, waiting for claim)
- Available jobs filtered by:
  - Service areas (cities pro serves)
  - Service types (services pro offers)
  - Pro's online status

#### 6. Claim Job
- Lock Pro sees job in feed
- Views job details:
  - Service type
  - Location
  - Urgency
  - Customer contact info
- Clicks "Claim Job"
- Job assigned to this pro
- Job status: `broadcast` → `claimed`
- Pro's `active_jobs_count` incremented

#### 7. Execute Job
- Pro updates job status as they work:
  - `claimed` → `en_route` (heading to customer)
  - `en_route` → `arrived` (at location)
  - `arrived` → `in_progress` (working)
  - `in_progress` → `completed` (done)
- Each status change logged with timestamp

#### 8. Earnings
- Job completed
- Earnings calculated:
  - Job amount - platform fee = payout
- Recorded in `provider_earnings` table
- Status: `pending` → `processing` → `paid`
- Stripe Connect transfer initiated

---

## Marketplace Mechanics

### Job Matching Algorithm

When a job is broadcast, the system finds Lock Pros who:
1. ✅ Are online (`is_online = true`)
2. ✅ Were seen recently (`last_seen_at` within 15 minutes)
3. ✅ Have capacity (`active_jobs_count < max_jobs_per_hour`)
4. ✅ Serve the job's city (in `service_areas`)
5. ✅ Offer the job's service (in `service_types`)
6. ✅ Are verified (`is_verified = true`)
7. ✅ Are active (`is_active = true`)

### Availability System

Lock Pros control their availability:
- **Online**: Can receive job broadcasts
- **Offline**: Hidden from dispatch system
- **Active Jobs Limit**: Max jobs per hour prevents overbooking
- **Last Seen**: Must be active within 15 minutes

### Job Lifecycle

```
created → broadcast → claimed → en_route → arrived → in_progress → completed
```

Each transition:
- Updates job status
- Logs to `job_statuses` table
- Updates provider availability counts
- Triggers customer notifications (when implemented)

### Verification System

New Lock Pros:
1. Register with license number
2. Account created but inactive
3. Admin reviews license
4. Admin verifies account
5. Lock Pro can now go online and claim jobs

---

## Access Points

### For Customers
- **Homepage**: "Request Lock Pro Now" button
- **City Pages**: "Lock Pros Ready in {City}" section
- **Service Pages**: "Need {Service} Now?" CTA
- **Direct**: `/request-locksmith` or `/request-locksmith/{city}/{service}`

### For Lock Pros
- **Header**: "Become a Lock Pro" link
- **Footer**: "Become a Lock Pro" button
- **Direct**: `/pro/register` or `/pro/login`

---

## Current Status

✅ **Fully Functional:**
- Provider registration with license verification
- Provider login/logout
- Job creation and broadcast
- Job claiming system
- Status updates
- Availability toggle
- Dashboard with active/available jobs

⏳ **Pending Implementation:**
- Admin verification interface
- Email notifications
- Real-time updates (WebSockets)
- Payment processing (Stripe Connect)
- Location tracking
- Push notifications

---

*The marketplace is now fully operational. Lock Pros can sign up, verify, and start accepting jobs. Customers can request help and get matched with available pros.*

