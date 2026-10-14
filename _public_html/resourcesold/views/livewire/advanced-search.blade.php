<div class="w-full max-w-3xl mx-auto p-4">
    <div class="flex flex-col md:flex-row gap-2 mb-4">
        <input type="text" wire:model.debounce.300ms="query" class="flex-1 border rounded px-3 py-2" placeholder="Rechercher un cours, une leçon, un formateur..." />
        <select wire:model="type" class="border rounded px-2 py-2">
            <option value="all">Tous types</option>
            <option value="courses">Cours</option>
            <option value="lessons">Leçons</option>
            <option value="users">Formateurs/Apprenants</option>
        </select>
    </div>
    <div class="flex flex-wrap gap-2 mb-4">
        @if($type === 'all' || $type === 'courses')
            <select wire:model="filters.category" class="border rounded px-2 py-1">
                <option value="">Catégorie</option>
                @foreach($categories as $cat)
                    <option value="{{ $cat }}">{{ $cat }}</option>
                @endforeach
            </select>
            <select wire:model="filters.level" class="border rounded px-2 py-1">
                <option value="">Niveau</option>
                @foreach($levels as $level)
                    <option value="{{ $level }}">{{ $level }}</option>
                @endforeach
            </select>
            <select wire:model="filters.price" class="border rounded px-2 py-1">
                <option value="">Prix</option>
                <option value="free">Gratuit</option>
                <option value="paid">Payant</option>
            </select>
        @endif
        @if($type === 'all' || $type === 'users')
            <select wire:model="filters.role" class="border rounded px-2 py-1">
                <option value="">Rôle</option>
                <option value="apprenant">Apprenant</option>
                <option value="formateur">Formateur</option>
                <option value="admin">Administrateur</option>
            </select>
        @endif
    </div>
    @if(!empty($suggestions) && strlen($query) >= 2)
        <div class="bg-white border rounded shadow p-2 mb-2">
            <div class="font-semibold text-gray-700 mb-1">Suggestions :</div>
            <ul>
                @foreach($suggestions as $suggestion)
                    <li class="py-1 text-green-700">{{ $suggestion->title }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <div>
        @if(strlen($query) < 2)
            <div class="text-gray-400">Tapez au moins 2 caractères pour lancer la recherche.</div>
        @elseif(empty($results) || $results->isEmpty())
            <div class="text-gray-500">Aucun résultat trouvé.</div>
        @else
            <ul class="divide-y">
                @foreach($results as $result)
                    <li class="py-2">
                        @if($result instanceof \App\Models\Course)
                            <div class="font-bold text-green-700">[Cours]</div>
                            <div>{{ $result->title }} <span class="text-xs text-gray-500">({{ $result->level }})</span></div>
                            <div class="text-sm text-gray-500">{{ $result->short_description }}</div>
                        @elseif($result instanceof \App\Models\Lesson)
                            <div class="font-bold text-blue-700">[Leçon]</div>
                            <div>{{ $result->title }}</div>
                            <div class="text-xs text-gray-500">Module : {{ optional($result->module)->title }}</div>
                        @elseif($result instanceof \App\Models\User)
                            <div class="font-bold text-orange-700">[Utilisateur]</div>
                            <div>{{ $result->first_name }} {{ $result->last_name }} <span class="text-xs text-gray-500">({{ $result->role }})</span></div>
                            <div class="text-sm text-gray-500">{{ $result->bio }}</div>
                        @endif
                    </li>
                @endforeach
            </ul>
        @endif
    </div>
</div> 