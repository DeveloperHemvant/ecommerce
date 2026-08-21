<x-layouts.admin title="Contact Enquiries - Sonakshi Admin" active="messages">
    <div class="space-y-6">
        <!-- Header -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h1 class="font-headline-lg text-heritage-burgundy">Contact Enquiries</h1>
                <p class="font-body-md text-xs text-on-surface-variant">Messages submitted through the storefront contact form.</p>
            </div>
        </div>

        @if(session('success'))
            <div class="p-4 bg-green-50 border border-green-200 text-green-800 text-xs rounded-xl flex items-center gap-2">
                <span class="material-symbols-outlined text-base">check_circle</span>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        <!-- Messages Table -->
        <div class="bg-surface-container-lowest rounded-2xl border border-border-subtle shadow-xs overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse text-xs">
                    <thead>
                        <tr class="bg-warm-ivory/60 border-b border-border-subtle font-label-caps text-on-surface-variant">
                            <th class="px-6 py-3.5 uppercase font-semibold">Contact</th>
                            <th class="px-6 py-3.5 uppercase font-semibold">Subject &amp; Message</th>
                            <th class="px-6 py-3.5 uppercase font-semibold">Date</th>
                            <th class="px-6 py-3.5 uppercase font-semibold">Status</th>
                            <th class="px-6 py-3.5 uppercase font-semibold text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-border-subtle font-body-md">
                        @forelse($messages as $msg)
                            <tr class="hover:bg-warm-ivory/40 transition-colors">
                                <td class="px-6 py-4">
                                    <p class="font-bold text-charcoal-text">{{ $msg->name }}</p>
                                    <p class="text-[11px] text-on-surface-variant">{{ $msg->email }}</p>
                                    @if($msg->phone)
                                        <p class="text-[11px] text-on-surface-variant">{{ $msg->phone }}</p>
                                    @endif
                                </td>
                                <td class="px-6 py-4 max-w-sm">
                                    <p class="font-bold text-charcoal-text text-xs">{{ $msg->subject }}</p>
                                    <p class="text-on-surface-variant text-[11px] line-clamp-2 mt-0.5">{{ $msg->message }}</p>
                                </td>
                                <td class="px-6 py-4 text-on-surface-variant font-data-tabular">
                                    {{ $msg->created_at->format('M d, Y') }}
                                </td>
                                <td class="px-6 py-4">
                                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-label-caps uppercase font-bold border {{ $msg->is_read ? 'bg-emerald-50 text-emerald-800 border-emerald-200' : 'bg-amber-50 text-amber-800 border-amber-200' }}">
                                        {{ $msg->is_read ? 'Read' : 'New' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <div class="flex items-center justify-center gap-2">
                                        @unless($msg->is_read)
                                            <form action="{{ route('admin.contact-messages.read', $msg) }}" method="POST">
                                                @csrf
                                                <button type="submit" class="px-2.5 py-1 text-[11px] font-label-caps uppercase font-bold rounded-lg border border-border-subtle hover:bg-surface-container transition-colors cursor-pointer">
                                                    Mark Read
                                                </button>
                                            </form>
                                        @endunless
                                        <form action="{{ route('admin.contact-messages.destroy', $msg) }}" method="POST" onsubmit="return confirm('Delete this message permanently?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="p-1 text-on-surface-variant hover:text-error transition-colors cursor-pointer" title="Delete">
                                                <span class="material-symbols-outlined text-lg">delete</span>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-12 text-center text-on-surface-variant">
                                    <span class="material-symbols-outlined text-3xl text-heritage-burgundy/40 block mb-1">mail</span>
                                    No contact enquiries yet.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($messages->hasPages())
                <div class="p-4 border-t border-border-subtle">
                    {{ $messages->links() }}
                </div>
            @endif
        </div>
    </div>
</x-layouts.admin>
