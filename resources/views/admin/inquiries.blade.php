@extends('layouts.admin')
@section('title', 'Inquiries')

@section('admin-content')
<div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:6px">
  <div class="admin-page-title" style="margin-bottom:0">Inquiries</div>
  <a href="{{ route('admin.inquiries.trash') }}" class="btn-outline" style="padding:10px 20px;font-size:13px">Trash ({{ $trashedCount }})</a>
</div>
<div class="admin-page-sub">Customer queries received from the website.</div>

<div class="admin-table-wrap">
  <table class="admin-table">
    <thead>
      <tr>
        <th>#</th><th>Name</th><th>Email</th><th>Mobile</th><th>Category</th>
        <th>Date</th><th>Status</th><th>Actions</th>
      </tr>
    </thead>
    <tbody>
      @forelse($inquiries as $i => $inq)
        <tr id="inq-row-{{ $inq->id }}">
          <td style="color:var(--text-dim)">{{ $i + 1 }}</td>
          <td style="font-weight:500">{{ $inq->name }}</td>
          <td>{{ $inq->user?->email ?? '-' }}</td>
          <td>{{ $inq->contact }}</td>
          <td>{{ $inq->category }}</td>
          <td style="color:var(--text-muted)">{{ $inq->created_at->format('d M Y') }}</td>
          <td><span class="badge badge-{{ strtolower($inq->status) }}" id="status-badge-{{ $inq->id }}">{{ $inq->status }}</span></td>
          <td>
            <div class="table-actions">
              <button class="icon-btn" onclick="viewInquiry({{ $inq->id }})" title="View">👁</button>
              <button class="icon-btn" onclick="promptResolveInquiry({{ $inq->id }})" title="Resolve Inquiry" style="color:#10b981">✓</button>
              <button class="icon-btn del" onclick="deleteInquiry({{ $inq->id }})" title="Delete">🗑</button>
            </div>
          </td>
        </tr>
      @empty
        <tr><td colspan="8" style="text-align:center;color:var(--text-dim);padding:32px">No inquiries yet.</td></tr>
      @endforelse
    </tbody>
  </table>
</div>

<!-- Inquiry Detail Modal -->
<div id="inqModal" class="hidden" style="position:fixed;inset:0;z-index:300;background:rgba(0,0,0,.7);display:flex;align-items:center;justify-content:center;padding:20px;backdrop-filter:blur(4px)">
  <div style="background:var(--dark2);border:1px solid var(--glass-border);border-radius:var(--radius);padding:32px;max-width:500px;width:100%;max-height:90vh;overflow-y:auto">
    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:20px">
      <span style="font-family:'Playfair Display',serif;font-size:20px;font-weight:700">Inquiry Details</span>
      <button onclick="document.getElementById('inqModal').classList.add('hidden')" style="background:none;border:none;color:var(--text-muted);font-size:20px;cursor:pointer">✕</button>
    </div>
    <div id="inqModalBody"></div>
    <div id="inqModalActions" style="display:flex;gap:12px;margin-top:20px"></div>
  </div>
</div>

<!-- Resolve Inquiry Modal -->
<div id="resolveModal" class="hidden" style="position:fixed;inset:0;z-index:300;background:rgba(0,0,0,.7);display:flex;align-items:center;justify-content:center;padding:20px;backdrop-filter:blur(4px)">
  <div style="background:var(--dark2);border:1px solid var(--glass-border);border-radius:var(--radius);padding:32px;max-width:500px;width:100%;max-height:90vh;overflow-y:auto">
    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:20px">
      <span style="font-family:'Playfair Display',serif;font-size:20px;font-weight:700;color:var(--text)">Resolve Inquiry & Send Email</span>
      <button onclick="document.getElementById('resolveModal').classList.add('hidden')" style="background:none;border:none;color:var(--text-muted);font-size:20px;cursor:pointer">✕</button>
    </div>
    <div id="resolveModalBody" style="margin-bottom:20px">
      <p style="font-size:11px;color:var(--text-muted);margin-bottom:6px;text-transform:uppercase;letter-spacing:1px;font-weight:600">Original Query Message</p>
      <div id="resolveModalQueryText" style="padding:14px;background:var(--dark3);border-radius:8px;font-size:13px;line-height:1.6;color:var(--text-muted);margin-bottom:18px;border:1px solid rgba(255,255,255,0.03);max-height:150px;overflow-y:auto"></div>
      
      <div style="color:var(--text-muted);font-size:11px;text-transform:uppercase;letter-spacing:1px;margin-bottom:8px;font-weight:600">Solution / Response Text</div>
      <textarea id="resolve_response_text" placeholder="Type the answer/solution here. An official email with this solution will be sent to the customer..." style="width:100%;height:120px;background:var(--dark3);border:1px solid var(--glass-border);border-radius:8px;padding:12px;color:var(--text);font-family:inherit;font-size:13px;resize:none;margin-bottom:12px;outline:none"></textarea>
    </div>
    <div style="display:flex;gap:12px">
      <button type="button" class="btn-gold" id="btnSubmitResolve" style="flex:1;font-size:13px;padding:12px;cursor:pointer" onclick="submitInquiryResolution()">Send Solution & Resolve</button>
      <button class="btn-outline" style="padding:12px;cursor:pointer" onclick="document.getElementById('resolveModal').classList.add('hidden')">Cancel</button>
    </div>
  </div>
</div>

@endsection

@push('scripts')
<script>
const INQUIRIES = @json($inquiries);

function viewInquiry(id) {
  const inq = INQUIRIES.find(x => x.id == id);
  if (!inq) return;
  const fmt = d => d ? new Date(d).toLocaleDateString('en-IN', {day:'2-digit',month:'short',year:'numeric'}) : '-';
  
  let responseHtml = '';
  if (inq.status === 'Pending') {
    responseHtml = `
      <div style="margin-top:20px; border-top:1px solid rgba(255,255,255,0.06); padding-top:16px;">
        <div style="color:var(--text-muted);font-size:11px;text-transform:uppercase;letter-spacing:1px;margin-bottom:8px;font-weight:600">Email Response to Customer</div>
        <textarea id="inq_response_text" placeholder="Type your custom email answer here..." style="width:100%;height:100px;background:var(--dark3);border:1px solid var(--glass-border);border-radius:8px;padding:12px;color:var(--text);font-family:inherit;font-size:13px;resize:none;margin-bottom:12px;outline:none"></textarea>
        <button type="button" class="btn-gold" id="btnSendEmailResponse" style="width:100%;font-size:13px;padding:10px" onclick="sendEmailResponse(${inq.id})">Send Email Response & Resolve</button>
      </div>`;
  } else if (inq.response) {
    responseHtml = `
      <div style="margin-top:20px; border-top:1px solid rgba(255,255,255,0.06); padding-top:16px;">
        <div style="color:var(--text-muted);font-size:11px;text-transform:uppercase;letter-spacing:1px;margin-bottom:8px;font-weight:600">Sent Email Response</div>
        <div style="padding:14px;background:rgba(201,168,76,0.05);border:1px solid var(--glass-border);border-left:3px solid var(--gold);border-radius:8px;font-size:13px;line-height:1.6;color:var(--text)">${inq.response.replace(/\n/g, '<br>')}</div>
      </div>`;
  }

  document.getElementById('inqModalBody').innerHTML = `
    <div style="display:flex;gap:12px;margin-bottom:12px;font-size:14px"><div style="color:var(--text-muted);min-width:100px;font-size:12px;text-transform:uppercase;letter-spacing:1px">Name</div><div>${inq.name}</div></div>
    <div style="display:flex;gap:12px;margin-bottom:12px;font-size:14px"><div style="color:var(--text-muted);min-width:100px;font-size:12px;text-transform:uppercase;letter-spacing:1px">Email</div><div>${inq.user ? inq.user.email : '-'}</div></div>
    <div style="display:flex;gap:12px;margin-bottom:12px;font-size:14px"><div style="color:var(--text-muted);min-width:100px;font-size:12px;text-transform:uppercase;letter-spacing:1px">Mobile</div><div>${inq.contact}</div></div>
    <div style="display:flex;gap:12px;margin-bottom:12px;font-size:14px"><div style="color:var(--text-muted);min-width:100px;font-size:12px;text-transform:uppercase;letter-spacing:1px">Category</div><div>${inq.category}</div></div>
    ${inq.product ? `<div style="display:flex;gap:12px;margin-bottom:12px;font-size:14px"><div style="color:var(--text-muted);min-width:100px;font-size:12px;text-transform:uppercase;letter-spacing:1px">Product</div><div>${inq.product}</div></div>` : ''}
    <div style="display:flex;gap:12px;margin-bottom:12px;font-size:14px"><div style="color:var(--text-muted);min-width:100px;font-size:12px;text-transform:uppercase;letter-spacing:1px">Date</div><div>${fmt(inq.created_at)}</div></div>
    <div style="display:flex;gap:12px;margin-bottom:12px;font-size:14px"><div style="color:var(--text-muted);min-width:100px;font-size:12px;text-transform:uppercase;letter-spacing:1px">Status</div><div><span class="badge badge-${inq.status === 'Pending' ? 'pending':'resolved'}" id="modal-status-${inq.id}">${inq.status}</span></div></div>
    <div style="margin-top:16px;padding:14px;background:var(--dark3);border-radius:8px;font-size:13px;line-height:1.6;color:var(--text-muted)">${inq.message || 'No message'}</div>
    ${responseHtml}`;

  const wa = encodeURIComponent(`Hi ${inq.name}, thanks for your inquiry about acrylic products from Manas Creations. How can we help?`);
  const digits = (inq.contact || '').replace(/\D/g,'');
  const phone = digits.length === 10 ? `91${digits}` : (digits.length === 11 && digits.startsWith('0') ? `91${digits.slice(1)}` : digits);
  document.getElementById('inqModalActions').innerHTML = `
    <a href="https://wa.me/${phone}?text=${wa}" target="_blank" class="btn-gold" style="display:flex;align-items:center;gap:8px">Reply on WhatsApp</a>
    <button class="btn-outline" onclick="document.getElementById('inqModal').classList.add('hidden')">Close</button>`;
  document.getElementById('inqModal').classList.remove('hidden');
}

async function sendEmailResponse(id) {
  const text = document.getElementById('inq_response_text').value.trim();
  const btn = document.getElementById('btnSendEmailResponse');
  
  if (!text || text.length < 5) {
    showToast('Please enter a response of at least 5 characters.', 'error');
    return;
  }
  
  btn.disabled = true;
  btn.textContent = 'Sending Response...';
  
  try {
    const res = await fetch(`/admin/inquiries/${id}/resolve`, {
      method: 'PATCH',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': window.CSRF_TOKEN,
        'Accept': 'application/json'
      },
      body: JSON.stringify({ response: text })
    });
    
    const data = await res.json();
    if (!res.ok) {
      throw new Error(data.message || 'Failed to email response.');
    }
    
    // Update local data array
    const inq = INQUIRIES.find(x => x.id == id);
    if (inq) {
      inq.status = data.status;
      inq.response = data.response;
    }
    
    // Remove the row from the active table list since it is resolved and soft-deleted!
    document.getElementById(`inq-row-${id}`)?.remove();
    
    document.getElementById('inqModal').classList.add('hidden');
    showToast('Response emailed & inquiry resolved successfully!');
  } catch(e) {
    showToast(e.message, 'error');
    btn.disabled = false;
    btn.textContent = 'Send Email Response & Resolve';
  }
}

let activeResolveInquiryId = null;

function promptResolveInquiry(id) {
  const inq = INQUIRIES.find(x => x.id == id);
  if (!inq) return;
  
  activeResolveInquiryId = id;
  document.getElementById('resolveModalQueryText').textContent = inq.message || 'No message';
  document.getElementById('resolve_response_text').value = '';
  document.getElementById('btnSubmitResolve').disabled = false;
  document.getElementById('btnSubmitResolve').textContent = 'Send Solution & Resolve';
  document.getElementById('resolveModal').classList.remove('hidden');
  
  setTimeout(() => {
    document.getElementById('resolve_response_text').focus();
  }, 100);
}

async function submitInquiryResolution() {
  const id = activeResolveInquiryId;
  if (!id) return;
  
  const text = document.getElementById('resolve_response_text').value.trim();
  const btn = document.getElementById('btnSubmitResolve');
  
  if (!text || text.length < 5) {
    showToast('Please enter a response of at least 5 characters.', 'error');
    return;
  }
  
  btn.disabled = true;
  btn.textContent = 'Sending Response...';
  
  try {
    const res = await fetch(`/admin/inquiries/${id}/resolve`, {
      method: 'PATCH',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': window.CSRF_TOKEN,
        'Accept': 'application/json'
      },
      body: JSON.stringify({ response: text })
    });
    
    const data = await res.json();
    if (!res.ok) {
      throw new Error(data.message || 'Failed to email response.');
    }
    
    // Remove the row from the active table list
    document.getElementById(`inq-row-${id}`)?.remove();
    
    // Close the modal
    document.getElementById('resolveModal').classList.add('hidden');
    showToast('Solution emailed & inquiry resolved successfully!');
  } catch(e) {
    showToast(e.message, 'error');
    btn.disabled = false;
    btn.textContent = 'Send Solution & Resolve';
  }
}

async function toggleStatus(id, current) {
  try {
    const res = await fetch(`/admin/inquiries/${id}/toggle`, {
      method: 'PATCH',
      headers: { 'X-CSRF-TOKEN': window.CSRF_TOKEN, 'Accept': 'application/json' }
    });
    const data = await res.json();
    const badge = document.getElementById(`status-badge-${id}`);
    if (badge) {
      badge.textContent = data.status;
      badge.className = `badge badge-${data.status === 'Pending' ? 'pending' : 'resolved'}`;
    }
    // Update local data
    const inq = INQUIRIES.find(x => x.id == id);
    if (inq) inq.status = data.status;
    showToast('Status updated.');
  } catch(e) { showToast('Update failed.', 'error'); }
}

async function deleteInquiry(id) {
  if (!confirm('Delete this inquiry?')) return;
  try {
    await fetch(`/admin/inquiries/${id}`, {
      method: 'DELETE',
      headers: { 'X-CSRF-TOKEN': window.CSRF_TOKEN }
    });
    document.getElementById(`inq-row-${id}`)?.remove();
    showToast('Inquiry deleted.');
  } catch(e) { showToast('Delete failed.', 'error'); }
}
</script>
@endpush
